<template>
    <Link 
        :href="route('shop.show', product.id)" 
        class="product-card flex flex-col group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative"
    >
        <!-- Discount Badge -->
        <div v-if="product.has_active_discount" class="absolute top-2 right-2 z-10 transform rotate-12">
            <div class="discount-badge shadow-lg">
                <div class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    -{{ product.discount_percentage }}%
                </div>
            </div>
        </div>

        <div class="aspect-square overflow-hidden bg-gray-200">
            <img 
                :src="product.cover_image_url" 
                :alt="product.title"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
            >
        </div>

        <div class="product-content p-4 flex-1 flex flex-col">
            <h3 class="product-title text-lg font-semibold text-jd-title group-hover:text-jd-accent transition-colors">
                {{ product.title }}
            </h3>
            
            <p v-if="product.description" class="text-sm text-jd mt-1 line-clamp-2 min-w-0">
                {{ product.description }}
            </p>
            
            <div class="flex items-center justify-between mt-3 flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <span v-if="product.has_active_discount" class="text-lg text-gray-500 line-through">
                        €{{ product.original_price }}
                    </span>
                    <span class="text-2xl font-bold text-jd-title">
                        €{{ product.price }}
                    </span>
                </div>
                <span class="link-jd text-sm font-medium">
                    Vedi Dettagli →
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

<style scoped>
.product-card {
    width: 100% !important;
    max-width: 100% !important;
}

.product-title {
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    max-width: 100% !important;
    word-wrap: break-word !important;
    word-break: break-all !important;
}

.product-content {
    overflow: hidden !important;
    width: 100% !important;
}
</style>
