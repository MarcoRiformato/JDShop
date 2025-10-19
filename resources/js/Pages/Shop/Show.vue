<template>
    <ShopLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <Link :href="route('shop.index')" class="text-blue-600 hover:text-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Shop
                </Link>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Image gallery -->
                <div>
                    <ProductGallery v-if="product.images.length > 0" :images="product.images" />
                    <div v-else class="aspect-square bg-gray-200 rounded-xl flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <!-- Product details -->
                <div class="flex flex-col">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            {{ product.title }}
                        </h1>

                        <!-- Price and status -->
                        <div class="flex items-center space-x-4 mb-6">
                            <span class="text-4xl font-bold text-gray-900">
                                ${{ product.price }}
                            </span>
                            <span 
                                v-if="product.sold_out"
                                class="px-4 py-2 bg-red-100 text-red-800 text-sm font-semibold rounded-full"
                            >
                                Sold Out
                            </span>
                            <span 
                                v-else
                                class="px-4 py-2 bg-green-100 text-green-800 text-sm font-semibold rounded-full"
                            >
                                Available
                            </span>
                        </div>

                        <!-- Description -->
                        <div v-if="product.description" class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">Description</h2>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ product.description }}
                            </p>
                        </div>

                        <!-- Tags -->
                        <div v-if="product.tags && product.tags.length > 0" class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">Tags</h2>
                            <div class="flex flex-wrap gap-2">
                                <Link
                                    v-for="tag in product.tags"
                                    :key="tag"
                                    :href="route('shop.index', { tag: tag })"
                                    class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-full text-sm font-medium transition-colors"
                                >
                                    {{ tag }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-auto pt-6 border-t border-gray-200">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-900">
                                <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Contact us for purchase inquiries and more information about this product.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related products section (placeholder) -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">You might also like</h2>
                <div class="text-center py-8 text-gray-500">
                    <p>More products coming soon!</p>
                </div>
            </div>
        </div>
    </ShopLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import ProductGallery from '@/Components/Shop/ProductGallery.vue';

defineProps({
    product: {
        type: Object,
        required: true,
    },
});
</script>

