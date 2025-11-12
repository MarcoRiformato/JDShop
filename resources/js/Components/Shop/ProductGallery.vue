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
import { ref, onMounted, onUnmounted } from 'vue';

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

const selectedImage = ref(props.images && props.images.length > 0 ? props.images[0] : null);
const showFullSize = ref(false);
const fullSizeImage = ref(null);
const currentImageIndex = ref(0);

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

