<template>
    <Head title="Crea Prodotto">
        <meta name="robots" content="noindex, nofollow">
    </Head>
    <AdminLayout>
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <Link :href="route('admin.dashboard')" class="text-blue-600 hover:text-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Torna alla Dashboard
                </Link>
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Crea Prodotto</h1>
                <p class="text-gray-600 mt-1">Aggiungi un nuovo prodotto al catalogo</p>
            </div>

            <!-- Global Error Message -->
            <div v-if="$page.props.flash?.error" class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $page.props.flash.error }}
                </div>
            </div>

            <!-- Uploading message -->
            <div v-if="productCreated && uploadingImages" class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <svg class="animate-spin h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Caricamento immagini in corso...
                </div>
            </div>

            <!-- Validation Errors Summary -->
            <div v-if="Object.keys(form.errors).length > 0" class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold">Per favore correggi i seguenti errori:</p>
                        <ul class="mt-2 list-disc list-inside">
                            <li v-for="(error, field) in form.errors" :key="field">
                                <span class="font-medium capitalize">{{ field.replace('_', ' ') }}:</span> {{ error }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Titolo <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="title"
                                v-model="form.title" 
                                type="text" 
                                maxlength="35"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.title }"
                                required
                            >
                            <div class="flex justify-between mt-1">
                                <p v-if="form.errors.title" class="text-sm text-red-600">{{ form.errors.title }}</p>
                                <p class="text-sm text-gray-500 ml-auto">{{ form.title.length }}/35</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Descrizione
                            </label>
                            <textarea 
                                id="description"
                                v-model="form.description" 
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.description }"
                            ></textarea>
                            <p v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</p>
                        </div>

                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">
                                Categorie
                            </label>
                            <input 
                                id="tags"
                                v-model="form.tags" 
                                type="text" 
                                placeholder="es: arredamento,specchio,vintage"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.tags }"
                            >
                            <p class="text-sm text-gray-500 mt-1">Separa le categorie con virgole</p>
                            <p v-if="form.errors.tags" class="text-sm text-red-600 mt-1">{{ form.errors.tags }}</p>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                Prezzo <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                                <input 
                                    id="price"
                                    v-model="form.price" 
                                    type="number" 
                                    step="0.01"
                                    min="0"
                                    class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.price }"
                                    required
                                >
                            </div>
                            <p v-if="form.errors.price" class="text-sm text-red-600 mt-1">{{ form.errors.price }}</p>
                        </div>

                        <!-- Sold Out -->
                        <div class="flex items-center">
                            <input 
                                id="sold_out"
                                v-model="form.sold_out" 
                                type="checkbox"
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="sold_out" class="ml-3 text-sm font-medium text-gray-700">
                                Segna come venduto
                            </label>
                        </div>

                        <!-- Image selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Immagini Prodotto
                            </label>
                            
                            <!-- Image picker -->
                            <div v-if="!productCreated">
                                <input 
                                    ref="fileInput"
                                    type="file" 
                                    accept="image/*"
                                    multiple
                                    @change="handleFileSelect"
                                    class="hidden"
                                >
                                <div 
                                    class="border-2 border-dashed rounded-lg p-6 text-center transition-colors cursor-pointer"
                                    :class="{
                                        'border-blue-500 bg-blue-50': isDragOver,
                                        'border-gray-300 hover:border-blue-500': !isDragOver
                                    }"
                                    @click="$refs.fileInput.click()"
                                    @dragover.prevent="handleDragOver"
                                    @dragenter.prevent="handleDragEnter"
                                    @dragleave.prevent="handleDragLeave"
                                    @drop.prevent="handleDrop"
                                >
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <p class="text-sm text-gray-600 mt-2">Clicca o trascina le immagini qui</p>
                                    <p class="text-xs text-gray-400 mt-1">Dimensione massima: 10MB per immagine</p>
                                    <p v-if="isDragOver" class="text-sm text-blue-600 font-medium mt-2">Rilascia qui</p>
                                </div>
                                
                                <!-- Preview selected images -->
                                <div v-if="selectedImages.length > 0" class="mt-4 grid grid-cols-2 gap-4">
                                    <div 
                                        v-for="(image, index) in selectedImages" 
                                        :key="index" 
                                        class="relative group cursor-pointer"
                                        @click="selectedCoverIndex = selectedCoverIndex === index ? null : index"
                                    >
                                        <div 
                                            class="relative overflow-hidden rounded-lg"
                                            :class="selectedCoverIndex === index ? 'ring-4 ring-blue-500' : 'border-2 border-gray-200'"
                                        >
                                            <img 
                                                :src="image.preview" 
                                                alt="Preview" 
                                                class="w-full h-32 object-cover"
                                            >
                                            <button
                                                @click.stop="removeImage(index)"
                                                type="button"
                                                class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                            </button>
                                            
                                            <!-- Cover badge -->
                                            <div v-if="selectedCoverIndex === index" class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">
                                                Copertina
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 truncate">{{ image.name }}</p>
                                    </div>
                                </div>
                                <p v-if="selectedImages.length > 0" class="text-xs text-gray-500 mt-2">
                                    💡 Clicca su un'immagine per impostarla come copertina
                                </p>
                            </div>
                            
                            <!-- Upload progress (shown during upload) -->
                            <div v-if="productCreated && uploadingImages" class="space-y-2">
                                <div v-for="(progress, index) in uploadProgress" :key="index" class="bg-white rounded-lg border p-3">
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
                        </div>

                        <!-- Submit button -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <Link 
                                :href="route('admin.dashboard')" 
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                Annulla
                            </Link>
                            <button 
                                v-if="!productCreated && !uploadingImages"
                                type="submit" 
                                :disabled="form.processing"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ form.processing ? 'Creazione...' : 'Crea Prodotto' + (selectedImages.length > 0 ? ` e Carica ${selectedImages.length} Immagine${selectedImages.length > 1 ? 'i' : ''}` : '') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link, Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({
    title: '',
    description: '',
    tags: '',
    price: '',
    sold_out: false,
});

const productCreated = ref(false);
const createdProductId = ref(null);
const selectedImages = ref([]);
const selectedCoverIndex = ref(null); // Index of the image to be set as cover
const uploadProgress = ref([]);
const uploadingImages = ref(false);
const isDragOver = ref(false);
const fileInput = ref(null);

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

    const files = Array.from(event.dataTransfer.files).filter(file => file.type.startsWith('image/'));
    addImages(files);
};

const handleFileSelect = (event) => {
    const files = Array.from(event.target.files);
    addImages(files);
    // Reset input
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const addImages = (files) => {
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    files.forEach(file => {
        if (file.size > maxSize) {
            alert(`${file.name} è troppo grande. Dimensione massima: 10MB`);
            return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
            selectedImages.value.push({
                file: file,
                name: file.name,
                preview: e.target.result,
            });
        };
        reader.readAsDataURL(file);
    });
};

const removeImage = (index) => {
    selectedImages.value.splice(index, 1);
};

const submit = async () => {
    // First, create the product
    await form.post(route('products.store'), {
        onSuccess: async (page) => {
            // Extract product ID from the redirect URL
            const productId = extractProductIdFromUrl(page.url);
            if (productId && selectedImages.value.length > 0) {
                createdProductId.value = productId;
                productCreated.value = true;
                
                // Upload all selected images
                await uploadImages(productId);
            } else if (productId) {
                // No images to upload, just redirect
                router.visit(route('products.edit', productId));
            }
        }
    });
};

const extractProductIdFromUrl = (url) => {
    const match = url.match(/\/admin\/products\/(\d+)\/edit/);
    return match ? parseInt(match[1]) : null;
};

const uploadImages = async (productId) => {
    uploadingImages.value = true;
    uploadProgress.value = selectedImages.value.map(img => ({ name: img.name, percent: 0 }));

    const uploadedImageIds = [];

    for (let i = 0; i < selectedImages.value.length; i++) {
        const img = selectedImages.value[i];
        const formData = new FormData();
        formData.append('image', img.file);

        try {
            const response = await axios.post(
                route('products.images.upload', productId),
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
            
            if (response.data.success && response.data.image) {
                uploadedImageIds.push({
                    index: i,
                    imageId: response.data.image.id
                });
            }
        } catch (error) {
            console.error(`Error uploading ${img.name}:`, error);
        }
    }

    // If a cover was selected, set it
    if (selectedCoverIndex.value !== null && uploadedImageIds.length > 0) {
        const coverImage = uploadedImageIds.find(img => img.index === selectedCoverIndex.value);
        if (coverImage) {
            try {
                await axios.patch(route('images.set-cover', coverImage.imageId));
            } catch (error) {
                console.error('Error setting cover image:', error);
            }
        }
    }

    uploadingImages.value = false;
    
    // Redirect to edit page after upload completes
    setTimeout(() => {
        router.visit(route('products.edit', productId));
    }, 500);
};
</script>

