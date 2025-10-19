<template>
    <div class="space-y-4">
        <!-- Main image -->
        <div class="aspect-square overflow-hidden rounded-xl bg-gray-200 shadow-lg">
            <img 
                :src="selectedImage.url" 
                :alt="'Product image'"
                class="w-full h-full object-cover"
            >
        </div>

        <!-- Thumbnail navigation -->
        <div v-if="images.length > 1" class="grid grid-cols-4 gap-2">
            <button
                v-for="image in images"
                :key="image.id"
                @click="selectedImage = image"
                class="aspect-square overflow-hidden rounded-lg border-2 transition-all hover:border-blue-500"
                :class="selectedImage.id === image.id ? 'border-blue-600 ring-2 ring-blue-200' : 'border-gray-200'"
            >
                <img 
                    :src="image.thumbnail_url" 
                    :alt="'Product thumbnail'"
                    class="w-full h-full object-cover"
                >
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    images: {
        type: Array,
        required: true,
    },
});

const selectedImage = ref(props.images[0]);

onMounted(() => {
    // Select cover image if available
    const coverImage = props.images.find(img => img.is_cover);
    if (coverImage) {
        selectedImage.value = coverImage;
    }
});
</script>

