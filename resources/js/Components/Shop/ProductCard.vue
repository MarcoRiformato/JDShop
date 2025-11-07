<template>
    <Link 
        :href="route('shop.show', product.id)" 
        class="product-card flex flex-col group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative"
        :class="{ 'opacity-90': product.sold_out }"
    >
        <!-- Sold Out Badge -->
        <div v-if="product.sold_out" class="absolute top-1.5 left-1.5 z-10">
            <div class="bg-gray-800 text-white px-2 py-1 rounded-md shadow-lg font-bold text-xs">
                VENDUTO
            </div>
        </div>

        <!-- Discount Badge -->
        <div v-if="product.has_active_discount && !product.sold_out" class="absolute top-1.5 right-1.5 z-10 transform rotate-12">
            <div class="discount-badge shadow-lg">
                <div class="text-sm font-bold">
                    -{{ product.discount_percentage }}%
                </div>
            </div>
        </div>

        <div class="aspect-square overflow-hidden bg-gray-200 relative">
            <img 
                :src="product.cover_image_url" 
                :alt="product.title"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                :class="{ 'filter grayscale opacity-60': product.sold_out }"
            >
        </div>

        <div class="product-content p-3 flex-1 flex flex-col">
            <h3 class="product-title text-base font-semibold text-jd-title group-hover:text-jd-accent transition-colors">
                {{ product.title }}
            </h3>
            
            <p v-if="product.description" class="text-sm text-jd mt-1.5 line-clamp-1 min-w-0">
                {{ product.description }}
            </p>
            
            <div class="flex items-center justify-between mt-2.5 flex-wrap gap-1.5">
                <div class="flex items-center gap-2">
                    <span v-if="product.has_active_discount" class="text-base text-gray-500 line-through">
                        €{{ product.original_price }}
                    </span>
                    <span class="text-xl font-bold text-jd-title">
                        €{{ product.price }}
                    </span>
                </div>
                <span class="link-jd text-sm font-medium">
                    Vedi →
                </span>
            </div>
            
            <div v-if="product.tags && product.tags.length > 0" class="flex flex-wrap gap-1 mt-2">
                <span 
                    v-for="tag in product.tags.slice(0, 3)" 
                    :key="tag"
                    class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full"
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
