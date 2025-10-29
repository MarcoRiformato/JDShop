<template>
    <div class="space-y-4">
        <div 
            class="border-2 border-dashed rounded-lg p-6 text-center transition-colors"
            :class="{
                'border-blue-500 bg-blue-50': isDragOver,
                'border-gray-300 hover:border-blue-500': !isDragOver
            }"
            @dragover.prevent="handleDragOver"
            @dragenter.prevent="handleDragEnter"
            @dragleave.prevent="handleDragLeave"
            @drop.prevent="handleDrop"
        >
            <!-- Standard file input for gallery/library selection -->
            <input 
                ref="fileInput"
                type="file" 
                accept="image/*"
                multiple
                @change="handleFileSelect"
                class="hidden"
            >
            <!-- Camera-specific input for mobile devices -->
            <input 
                ref="cameraInput"
                type="file" 
                accept="image/*"
                capture="environment"
                multiple
                @change="handleFileSelect"
                class="hidden"
            >
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button 
                    @click="openFilePicker('gallery')"
                    type="button"
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md"
                >
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Seleziona da Galleria
                </button>
                <button 
                    @click="openFilePicker('camera')"
                    type="button"
                    class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md"
                >
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Scatta Foto
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-2">
                Scegli una fonte oppure trascina e rilascia le immagini qui
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Dimensione massima: 20MB per immagine (verrà compressa automaticamente)
            </p>
            <p v-if="isDragOver" class="text-sm text-blue-600 font-medium mt-2">
                Rilascia qui per caricare
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
const cameraInput = ref(null);
const uploading = ref(false);
const uploadProgress = ref([]);
const errors = ref([]);
const isDragOver = ref(false);

// Open file picker with specific source
const openFilePicker = (source) => {
    if (source === 'camera' && cameraInput.value) {
        cameraInput.value.click();
    } else if (source === 'gallery' && fileInput.value) {
        fileInput.value.click();
    }
};

// Drag and drop handlers
const handleDragEnter = (event) => {
    event.preventDefault();
    event.stopPropagation();
    isDragOver.value = true;
};

const handleDragOver = (event) => {
    event.preventDefault();
    event.stopPropagation();
};

const handleDragLeave = (event) => {
    event.preventDefault();
    event.stopPropagation();
    isDragOver.value = false;
};

const handleDrop = (event) => {
    event.preventDefault();
    event.stopPropagation();
    isDragOver.value = false;

    const files = Array.from(event.dataTransfer.files);
    
    if (files.length > 0) {
        // Convert FileList to files array for processing
        processFiles(files);
    }
};

const handleFileSelect = async (event) => {
    const files = Array.from(event.target.files);
    
    if (files.length === 0) return;

    await processFiles(files);
};

// Compress image using canvas
const compressImage = (file, maxWidth = 1920, quality = 0.85) => {
    return new Promise((resolve, reject) => {
        // Always compress images from mobile devices, or if file is larger than 1MB
        // This ensures mobile photos are always compressed
        if (!file.type.startsWith('image/')) {
            resolve(file);
            return;
        }
        
        // For very small files (< 500KB), return as-is to avoid unnecessary processing
        if (file.size < 500 * 1024) {
            resolve(file);
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                // Calculate new dimensions
                if (width > maxWidth) {
                    height = (height / width) * maxWidth;
                    width = maxWidth;
                }

                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(
                    (blob) => {
                        if (blob) {
                            // Create a new File object with original name
                            const compressedFile = new File([blob], file.name, {
                                type: 'image/jpeg',
                                lastModified: Date.now(),
                            });
                            resolve(compressedFile);
                        } else {
                            resolve(file); // Fallback to original if compression fails
                        }
                    },
                    'image/jpeg',
                    quality
                );
            };
            img.onerror = () => {
                resolve(file); // Fallback to original if image load fails
            };
            img.src = e.target.result;
        };
        reader.onerror = () => {
            resolve(file); // Fallback to original if read fails
        };
        reader.readAsDataURL(file);
    });
};

const processFiles = async (files) => {
    errors.value = [];
    uploading.value = true;
    uploadProgress.value = files.map(file => ({ name: file.name, percent: 0 }));

    // Process and compress files BEFORE size check to ensure they fit
    const compressedFiles = [];
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Update progress to show compression
        uploadProgress.value[i].percent = 5;
        
        // Always compress images (especially important for mobile photos which are often large)
        // Compress with lower quality and max width to ensure files are small enough
        const processedFile = await compressImage(file, 1920, 0.80);
        compressedFiles.push(processedFile);
        
        uploadProgress.value[i].percent = 10;
        
        // Check compressed file size (10MB limit for backend)
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes (matches backend validation)
        if (processedFile.size > maxSize) {
            errors.value.push(`${file.name} è ancora troppo grande dopo la compressione (max 10MB). Dimensione: ${(processedFile.size / 1024 / 1024).toFixed(2)}MB`);
        }
    }

    // If there are errors, don't proceed with upload
    if (errors.value.length > 0) {
        uploading.value = false;
        uploadProgress.value = [];
        if (fileInput.value) {
            fileInput.value.value = '';
        }
        return;
    }

    // Upload compressed files
    for (let i = 0; i < compressedFiles.length; i++) {
        const processedFile = compressedFiles[i];
        const file = files[i];
        
        const formData = new FormData();
        formData.append('image', processedFile);

        try {
            const response = await axios.post(
                route('products.images.upload', props.productId),
                formData,
                {
                    headers: { 'Content-Type': 'multipart/form-data' },
                    onUploadProgress: (progressEvent) => {
                        // Scale progress from 10% to 100% (since compression was 10%)
                        uploadProgress.value[i].percent = Math.round(
                            10 + (progressEvent.loaded * 90) / progressEvent.total
                        );
                    },
                }
            );

            if (response.data.success) {
                emit('uploaded', response.data.image);
            }
        } catch (error) {
            let errorMessage = `Impossibile caricare ${file.name}`;
            
            // Handle specific error cases
            if (error.response?.status === 413 || error.response?.status === 400) {
                errorMessage += ': File troppo grande. Dimensione massima: 20MB';
            } else if (error.response?.status === 422) {
                errorMessage += ': ' + (error.response?.data?.message || 'Formato non supportato');
            } else if (error.response?.data?.message) {
                errorMessage += ': ' + error.response.data.message;
            } else if (error.message) {
                errorMessage += ': ' + error.message;
            }
            
            errors.value.push(errorMessage);
        }
    }

    uploading.value = false;
    uploadProgress.value = [];
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};
</script>

