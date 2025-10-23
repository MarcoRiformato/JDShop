<template>
    <div class="space-y-4">
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
            <input 
                ref="fileInput"
                type="file" 
                accept="image/*" 
                capture="environment"
                multiple
                @change="handleFileSelect"
                class="hidden"
            >
            <button 
                @click="$refs.fileInput.click()"
                type="button"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md"
            >
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Scatta Foto / Seleziona Immagini
            </button>
            <p class="text-sm text-gray-500 mt-2">
                Oppure trascina e rilascia le immagini qui
            </p>
        </div>

        <!-- Upload progress -->
        <div v-if="uploading" class="space-y-2">
            <div v-for="(progress, index) in uploadProgress" :key="index" class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">{{ progress.name }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ progress.percent }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div 
                        class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                        :style="{ width: progress.percent + '%' }"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Error messages -->
        <div v-if="errors.length > 0" class="space-y-2">
            <div 
                v-for="(error, index) in errors" 
                :key="index"
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"
            >
                {{ error }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    productId: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['uploaded']);

const fileInput = ref(null);
const uploading = ref(false);
const uploadProgress = ref([]);
const errors = ref([]);

const handleFileSelect = async (event) => {
    const files = Array.from(event.target.files);
    
    if (files.length === 0) return;

    errors.value = [];
    uploading.value = true;
    uploadProgress.value = files.map(file => ({ name: file.name, percent: 0 }));

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const formData = new FormData();
        formData.append('image', file);

        try {
            const response = await axios.post(
                route('products.images.upload', props.productId),
                formData,
                {
                    headers: { 'Content-Type': 'multipart/form-data' },
                    onUploadProgress: (progressEvent) => {
                        uploadProgress.value[i].percent = Math.round(
                            (progressEvent.loaded * 100) / progressEvent.total
                        );
                    },
                }
            );

            if (response.data.success) {
                emit('uploaded', response.data.image);
            }
        } catch (error) {
            errors.value.push(`Impossibile caricare ${file.name}: ${error.response?.data?.message || error.message}`);
        }
    }

    uploading.value = false;
    uploadProgress.value = [];
    fileInput.value.value = '';
};
</script>

