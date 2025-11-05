<template>
    <div class="space-y-4">
        <!-- Main image -->
        <div 
            v-if="selectedImage"
            class="aspect-square overflow-hidden rounded-xl bg-gray-200 shadow-lg cursor-pointer hover:opacity-90 transition-opacity touch-manipulation"
            @click="openFullSizeView(selectedImage)"
        >
            <img 
                :src="selectedImage.url" 
                :alt="'Product image'"
                class="w-full h-full object-cover pointer-events-none"
            >
        </div>
        <div v-else class="aspect-square overflow-hidden rounded-xl bg-gray-200 shadow-lg flex items-center justify-center">
            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>

        <!-- Thumbnail navigation -->
        <div v-if="images.length > 1" class="grid grid-cols-4 gap-2">
            <button
                v-for="image in images"
                :key="image.id"
                @click="handleThumbnailClick(image, $event)"
                class="aspect-square overflow-hidden rounded-lg border-2 transition-all hover:border-blue-500 cursor-pointer"
                :class="selectedImage.id === image.id ? 'border-blue-600 ring-2 ring-blue-200' : 'border-gray-200'"
            >
                <img 
                    :src="image.thumbnail_url" 
                    :alt="'Product thumbnail'"
                    class="w-full h-full object-cover"
                >
            </button>
        </div>

        <!-- Full-size Image Modal -->
        <div 
            v-if="showFullSize"
            @click="closeFullSize"
            class="fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center p-4"
        >
            <div 
                @click.stop
                class="relative max-w-7xl max-h-full w-full h-full flex flex-col"
            >
                <!-- Close button -->
                <button
                    @click="closeFullSize"
                    class="absolute top-4 right-4 z-20 p-2 bg-jd-base-light bg-opacity-20 hover:bg-opacity-30 rounded-full text-white transition-colors"
                    aria-label="Chiudi"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Full-size image -->
                <div class="flex-1 flex items-center justify-center overflow-hidden" v-if="fullSizeImage">
                    <img 
                        :src="fullSizeImage.url" 
                        :alt="'Full size product image'"
                        class="max-w-full max-h-full object-contain"
                    >
                </div>

                <!-- Admin actions (if logged in as admin) -->
                <div v-if="isAdmin" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-3 bg-black bg-opacity-50 rounded-lg p-3 backdrop-blur-sm">
                    <button
                        @click="setAsCopertina"
                        :disabled="fullSizeImage.is_cover || isProcessing"
                        class="px-4 py-2 bg-jd-accent text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 touch-manipulation"
                        :title="fullSizeImage.is_cover ? 'Già copertina' : 'Imposta come copertina'"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span class="hidden sm:inline">{{ fullSizeImage.is_cover ? 'Copertina' : 'Imposta Copertina' }}</span>
                    </button>
                    <button
                        @click="deleteImage"
                        :disabled="isProcessing"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 touch-manipulation"
                        title="Elimina immagine"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span class="hidden sm:inline">Elimina</span>
                    </button>
                </div>

                <!-- Navigation arrows (if multiple images) -->
                <button
                    v-if="images.length > 1 && currentImageIndex > 0"
                    @click.stop="previousImage"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 p-3 bg-jd-base-light bg-opacity-20 hover:bg-opacity-30 rounded-full text-white transition-colors touch-manipulation"
                    aria-label="Immagine precedente"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button
                    v-if="images.length > 1 && currentImageIndex < images.length - 1"
                    @click.stop="nextImage"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 p-3 bg-jd-base-light bg-opacity-20 hover:bg-opacity-30 rounded-full text-white transition-colors touch-manipulation"
                    aria-label="Immagine successiva"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Image counter -->
                <div v-if="images.length > 1" class="absolute top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 bg-black bg-opacity-50 rounded-full text-white text-sm backdrop-blur-sm">
                    {{ currentImageIndex + 1 }} / {{ images.length }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    images: {
        type: Array,
        required: true,
    },
    productId: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['image-deleted', 'cover-updated']);

const page = usePage();
const isAdmin = computed(() => page.props.auth?.user?.is_admin || false);

const selectedImage = ref(props.images && props.images.length > 0 ? props.images[0] : null);
const showFullSize = ref(false);
const fullSizeImage = ref(null);
const currentImageIndex = ref(0);
const isProcessing = ref(false);

onMounted(() => {
    // Ensure we have images
    if (!props.images || props.images.length === 0) {
        return;
    }
    
    // Select cover image if available, otherwise first image
    const coverImage = props.images.find(img => img.is_cover);
    if (coverImage) {
        selectedImage.value = coverImage;
    } else {
        selectedImage.value = props.images[0];
    }
});

const handleThumbnailClick = (image, event) => {
    // Always open full-size view when clicking thumbnail
    openFullSizeView(image);
};

const openFullSizeView = (image) => {
    if (!image) return;
    fullSizeImage.value = image;
    currentImageIndex.value = props.images.findIndex(img => img.id === image.id);
    showFullSize.value = true;
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
};

const closeFullSize = () => {
    showFullSize.value = false;
    document.body.style.overflow = '';
};

const previousImage = () => {
    if (currentImageIndex.value > 0) {
        currentImageIndex.value--;
        fullSizeImage.value = props.images[currentImageIndex.value];
    }
};

const nextImage = () => {
    if (currentImageIndex.value < props.images.length - 1) {
        currentImageIndex.value++;
        fullSizeImage.value = props.images[currentImageIndex.value];
    }
};

const setAsCopertina = async () => {
    if (!fullSizeImage.value || fullSizeImage.value.is_cover || isProcessing.value) return;
    
    isProcessing.value = true;
    try {
        await axios.patch(route('images.set-cover', fullSizeImage.value.id));
        
        // Update the image in the list
        const imageIndex = props.images.findIndex(img => img.id === fullSizeImage.value.id);
        if (imageIndex !== -1) {
            // Update all images
            props.images.forEach(img => {
                img.is_cover = img.id === fullSizeImage.value.id;
            });
            fullSizeImage.value.is_cover = true;
            selectedImage.value = fullSizeImage.value;
        }
        
        emit('cover-updated', fullSizeImage.value);
        
        // Show success message
        alert('Immagine impostata come copertina con successo!');
    } catch (error) {
        alert('Errore durante l\'impostazione della copertina: ' + (error.response?.data?.message || error.message));
    } finally {
        isProcessing.value = false;
    }
};

const deleteImage = async () => {
    if (!fullSizeImage.value || isProcessing.value) return;
    
    if (!confirm('Sei sicuro di voler eliminare questa immagine?')) return;
    
    isProcessing.value = true;
    try {
        await axios.delete(route('images.delete', fullSizeImage.value.id));
        
        emit('image-deleted', fullSizeImage.value.id);
        
        // Close modal if this was the only image
        if (props.images.length === 1) {
            closeFullSize();
        } else {
            // Navigate to previous or next image
            if (currentImageIndex.value > 0) {
                previousImage();
            } else if (currentImageIndex.value < props.images.length - 1) {
                nextImage();
            } else {
                closeFullSize();
            }
        }
        
        alert('Immagine eliminata con successo!');
    } catch (error) {
        alert('Errore durante l\'eliminazione dell\'immagine: ' + (error.response?.data?.message || error.message));
    } finally {
        isProcessing.value = false;
    }
};

// Keyboard navigation
const handleKeyDown = (event) => {
    if (!showFullSize.value) return;
    
    if (event.key === 'Escape') {
        closeFullSize();
    } else if (event.key === 'ArrowLeft' && currentImageIndex.value > 0) {
        previousImage();
    } else if (event.key === 'ArrowRight' && currentImageIndex.value < props.images.length - 1) {
        nextImage();
    }
};

const setupKeyboardNavigation = () => {
    window.addEventListener('keydown', handleKeyDown);
};

onMounted(() => {
    setupKeyboardNavigation();
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    document.body.style.overflow = '';
});
</script>

