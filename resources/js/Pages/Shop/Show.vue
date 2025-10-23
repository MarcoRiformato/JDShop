<template>
    <ShopLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <Link :href="route('shop.index')" class="text-blue-600 hover:text-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Torna al Catalogo
                </Link>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Image gallery -->
                <div>
                    <ProductGallery v-if="product.images.length > 0" :images="product.images" />
                    <div v-else class="aspect-square bg-gray-200 rounded-xl overflow-hidden">
                        <img 
                            :src="`https://picsum.photos/seed/${product.id}/800/800`" 
                            :alt="product.title"
                            class="w-full h-full object-cover"
                        >
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
                                €{{ product.price }}
                            </span>
                            <span 
                                v-if="product.sold_out"
                                class="px-4 py-2 bg-red-100 text-red-800 text-sm font-semibold rounded-full"
                            >
                                Venduto
                            </span>
                            <span 
                                v-else
                                class="px-4 py-2 bg-green-100 text-green-800 text-sm font-semibold rounded-full"
                            >
                                Disponibile
                            </span>
                        </div>

                        <!-- Description -->
                        <div v-if="product.description" class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">Descrizione</h2>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ product.description }}
                            </p>
                        </div>

                        <!-- Tags -->
                        <div v-if="product.tags && product.tags.length > 0" class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">Categorie</h2>
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
                                Contattaci per informazioni sull'acquisto e per maggiori dettagli su questo prodotto.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related products section (placeholder) -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Potrebbero interessarti anche</h2>
                <div class="text-center py-8 text-gray-500">
                    <p>Altri prodotti in arrivo presto!</p>
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

