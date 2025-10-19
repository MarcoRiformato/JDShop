<template>
    <Link 
        :href="route('shop.show', product.id)" 
        class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
    >
        <div class="aspect-square overflow-hidden bg-gray-200">
            <img 
                v-if="product.cover_image_url" 
                :src="product.cover_image_url" 
                :alt="product.title"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
            >
            <div v-else class="w-full h-full flex items-center justify-center">
                <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                {{ product.title }}
            </h3>
            <p v-if="product.description" class="text-sm text-gray-600 mt-1 line-clamp-2">
                {{ product.description }}
            </p>
            <div class="flex items-center justify-between mt-3">
                <span class="text-2xl font-bold text-gray-900">
                    ${{ product.price }}
                </span>
                <span class="text-sm text-blue-600 group-hover:text-blue-700 font-medium">
                    View Details →
                </span>
            </div>
            <div v-if="product.tags && product.tags.length > 0" class="flex flex-wrap gap-1 mt-3">
                <span 
                    v-for="tag in product.tags.slice(0, 3)" 
                    :key="tag"
                    class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full"
                >
                    {{ tag }}
                </span>
            </div>
        </div>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: {
        type: Object,
        required: true,
    },
});
</script>

