<template>
    <Head title="Crea Prodotto">
        <meta name="robots" content="noindex, nofollow">
    </Head>
    <AdminLayout>
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <Link :href="route('admin.dashboard')" class="link-jd flex items-center font-medium">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Torna alla Dashboard
                </Link>
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Crea Prodotto</h1>
                <p class="text-jd mt-1">Aggiungi un nuovo prodotto al catalogo</p>
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
            <div v-if="productCreated && uploadingImages" class="mb-6 bg-blue-50 border border-blue-400 text-gray-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <svg class="animate-spin h-5 w-5 text-jd-accent mr-2" fill="none" viewBox="0 0 24 24">
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
                                class="input-jd w-full"
                                :class="{ 'border-red-500': form.errors.title }"
                                required
                            >
                            <div class="flex justify-between mt-1">
                                <p v-if="form.errors.title" class="text-sm text-red-400">{{ form.errors.title }}</p>
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
                                class="input-jd w-full"
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
                                class="input-jd w-full"
                                :class="{ 'border-red-500': form.errors.tags }"
                            >
                            <p class="text-sm text-gray-500 mt-1">Separa le categorie con virgole</p>
                            <p v-if="form.errors.tags" class="text-sm text-red-600 mt-1">{{ form.errors.tags }}</p>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                Prezzo <span class="text-red-400">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600 text-base font-medium flex-shrink-0">€</span>
                                <input 
                                    id="price"
                                    v-model="form.price" 
                                    type="number" 
                                    step="0.01"
                                    min="0"
                                    class="input-jd flex-1"
                                    :class="{ 'border-red-500': form.errors.price }"
                                    placeholder="0.00"
                                    required
                                >
                            </div>
                            <p v-if="form.errors.price" class="text-sm text-red-600 mt-1">{{ form.errors.price }}</p>
                        </div>

                        <!-- Sold Out - Only for full admins, not editors -->
                        <div v-if="$page.props.auth.user.is_full_admin" class="flex items-center">
                            <input 
                                id="sold_out"
                                v-model="form.sold_out" 
                                type="checkbox"
                                class="h-5 w-5 text-jd-accent focus:ring-jd-accent border-gray-300 rounded"
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
                                <div 
                                    class="border-2 border-dashed rounded-lg p-6 text-center transition-colors"
                                    :class="{
                                        'border-gray-300-accent bg-gray-50': isDragOver,
                                        'border-gray-300': !isDragOver
                                    }"
                                    @dragover.prevent="handleDragOver"
                                    @dragenter.prevent="handleDragEnter"
                                    @dragleave.prevent="handleDragLeave"
                                    @drop.prevent="handleDrop"
                                >
                                    <svg class="w-12 h-12 mx-auto text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <div class="flex flex-col sm:flex-row gap-3 justify-center mt-4">
                                        <button 
                                            @click="openFilePicker('gallery')"
                                            type="button"
                                            class="btn-jd-primary inline-flex items-center justify-center px-6 py-3 rounded-lg shadow-md"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Seleziona da Galleria
                                        </button>
                                        <button 
                                            @click="openFilePicker('camera')"
                                            type="button"
                                            class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Scatta Foto
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-4">Oppure trascina e rilascia le immagini qui</p>
                                    <p class="text-xs text-gray-500 mt-1">Dimensione massima: 20MB per immagine (verrà compressa automaticamente)</p>
                                    <p v-if="isDragOver" class="text-sm text-jd-accent font-medium mt-2">Rilascia qui</p>
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
                                            :class="selectedCoverIndex === index ? 'ring-4 ring-jd-accent' : 'border-2 border-gray-300'"
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
                                            <div v-if="selectedCoverIndex === index" class="absolute top-2 left-2 bg-jd-accent text-white text-xs font-bold px-2 py-1 rounded">
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
                                <div v-for="(progress, index) in uploadProgress" :key="index" class="bg-gray-50 rounded-lg border border-gray-300 p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-500">{{ progress.name }}</span>
                                        <span class="text-sm font-medium text-gray-700">{{ progress.percent }}%</span>
                                    </div>
                                    <div class="w-full bg-jd-base rounded-full h-2">
                                        <div 
                                            class="bg-jd-accent h-2 rounded-full transition-all duration-300"
                                            :style="{ width: progress.percent + '%' }"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-300">
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
                                class="btn-jd-primary px-6 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
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
const cameraInput = ref(null);

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

// Compress image using canvas (for ProductCreate)
const compressImage = (file, maxWidth = 1920, quality = 0.80) => {
    return new Promise((resolve) => {
        if (!file.type.startsWith('image/')) {
            resolve(file);
            return;
        }
        
        // For very small files (< 500KB), return as-is
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
                            const compressedFile = new File([blob], file.name, {
                                type: 'image/jpeg',
                                lastModified: Date.now(),
                            });
                            resolve(compressedFile);
                        } else {
                            resolve(file);
                        }
                    },
                    'image/jpeg',
                    quality
                );
            };
            img.onerror = () => {
                resolve(file);
            };
            img.src = e.target.result;
        };
        reader.onerror = () => {
            resolve(file);
        };
        reader.readAsDataURL(file);
    });
};

const addImages = async (files) => {
    const maxSize = 10 * 1024 * 1024; // 10MB (matches backend)
    
    for (const file of files) {
        // Compress image first (especially important for mobile photos)
        const compressedFile = await compressImage(file, 1920, 0.80);
        
        // Check compressed size
        if (compressedFile.size > maxSize) {
            alert(`${file.name} è troppo grande anche dopo la compressione. Dimensione: ${(compressedFile.size / 1024 / 1024).toFixed(2)}MB (max 10MB)`);
            continue;
        }
        
        // Create preview with original file for better preview quality
        const reader = new FileReader();
        reader.onload = (e) => {
            selectedImages.value.push({
                file: compressedFile, // Store compressed file for upload
                name: file.name,
                preview: e.target.result,
            });
        };
        reader.readAsDataURL(file); // Use original for preview
    }
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
            // Image upload failed - continue with other images
        }
    }

    // If a cover was selected, set it
    if (selectedCoverIndex.value !== null && uploadedImageIds.length > 0) {
        const coverImage = uploadedImageIds.find(img => img.index === selectedCoverIndex.value);
        if (coverImage) {
            try {
                await axios.patch(route('images.set-cover', coverImage.imageId));
            } catch (error) {
                // Failed to set cover image - continue anyway
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

