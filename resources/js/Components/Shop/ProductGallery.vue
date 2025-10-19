<template>
    <div class="space-y-4">
        <!-- Main image -->
        <div class="aspect-square overflow-hidden rounded-xl bg-gray-200 shadow-lg">
            <img 
                :src="selectedImageUrl" 
                :alt="'Product image'"
                class="w-full h-full object-cover"
            >
        </div>

        <!-- Thumbnail navigation -->
        <div v-if="images.length > 1" class="grid grid-cols-4 gap-2">
            <button
                v-for="(image, index) in images"
                :key="image.id"
                @click="selectedImage = image; selectedIndex = index"
                class="aspect-square overflow-hidden rounded-lg border-2 transition-all hover:border-blue-500"
                :class="selectedImage.id === image.id ? 'border-blue-600 ring-2 ring-blue-200' : 'border-gray-200'"
            >
                <img 
                    :src="getThumbnailUrl(image, index)" 
                    :alt="'Product thumbnail'"
                    class="w-full h-full object-cover"
                >
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    images: {
        type: Array,
        required: true,
    },
    productId: {
        type: Number,
        default: 0,
    },
});

const selectedImage = ref(props.images[0]);
const selectedIndex = ref(0);

const selectedImageUrl = computed(() => {
    if (selectedImage.value?.url) {
        return selectedImage.value.url;
    }
    // Fallback to Lorem Picsum if no URL
    return `https://picsum.photos/seed/${props.productId}-${selectedIndex.value}/800/800`;
});

const getThumbnailUrl = (image, index) => {
    if (image?.thumbnail_url) {
        return image.thumbnail_url;
    }
    // Fallback to Lorem Picsum
    return `https://picsum.photos/seed/${props.productId}-${index}/300/300`;
};

onMounted(() => {
    // Select cover image if available
    const coverImage = props.images.find(img => img.is_cover);
    if (coverImage) {
        selectedImage.value = coverImage;
        selectedIndex.value = props.images.indexOf(coverImage);
    }
});
</script>

