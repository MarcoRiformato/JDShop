<template>
    <Head :title="product.title">
        <meta name="description" :content="product.description || `${product.title} - Disponibile su JDOutlet al prezzo di €${product.price}`">
        <meta property="og:title" :content="`${product.title} - JDOutlet`">
        <meta property="og:description" :content="product.description || `${product.title} disponibile al prezzo di €${product.price}`">
        <meta v-if="product.images.length > 0" property="og:image" :content="product.images.find(img => img.is_cover)?.url || product.images[0].url">
        <meta property="og:url" :content="route('shop.show', product.id)">
        <meta property="og:type" content="product">
        <meta property="product:price:amount" :content="product.price">
        <meta property="product:price:currency" content="EUR">
        <meta name="twitter:title" :content="`${product.title} - JDOutlet`">
        <meta name="twitter:description" :content="product.description || `${product.title} disponibile al prezzo di €${product.price}`">
        <meta v-if="product.images.length > 0" name="twitter:image" :content="product.images.find(img => img.is_cover)?.url || product.images[0].url">
    </Head>
    <ShopLayout :tags="[]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-white rounded-lg my-6">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <Link :href="route('shop.index')" class="link-jd flex items-center font-medium">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Torna al Catalogo
                </Link>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Image gallery -->
                <div class="relative">
                    <!-- Sold Out Badge Overlay -->
                    <div v-if="product.sold_out" class="absolute top-4 left-4 z-20">
                        <div class="bg-gray-800 text-white px-6 py-3 rounded-lg shadow-2xl font-bold text-lg">
                            VENDUTO
                        </div>
                    </div>

                    <div :class="{ 'filter grayscale opacity-70': product.sold_out }">
                        <ProductGallery 
                            v-if="product.images.length > 0" 
                            :images="product.images"
                            :product-id="product.id"
                        />
                        <div v-else class="aspect-square bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center">
                            <div class="text-center p-8">
                                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-4 text-gray-500 text-sm">Nessuna immagine disponibile</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product details -->
                <div class="flex flex-col">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-jd-title mb-4">
                            {{ product.title }}
                        </h1>

                        <!-- Price and status -->
                        <div class="mb-6">
                            <!-- Sold Out Badge -->
                            <div v-if="product.sold_out" class="mb-4">
                                <span class="bg-gray-800 text-white inline-flex items-center px-6 py-3 text-lg rounded-lg font-bold shadow-lg">
                                    VENDUTO
                                </span>
                            </div>

                            <!-- Discount Badge -->
                            <div v-else-if="product.has_active_discount" class="mb-4">
                                <span class="discount-badge inline-flex items-center px-4 py-2 text-lg rounded-full">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    SCONTO {{ product.discount_percentage }}%
                                </span>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="flex items-center gap-3">
                                    <span v-if="product.has_active_discount && !product.sold_out" class="text-2xl text-gray-500 line-through">
                                        €{{ product.original_price }}
                                    </span>
                                    <span class="text-4xl font-bold text-jd-title" :class="{ 'opacity-60': product.sold_out }">
                                        €{{ product.price }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div v-if="product.description" class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Descrizione</h2>
                            <p class="text-jd leading-relaxed whitespace-pre-line">
                                {{ product.description }}
                            </p>
                        </div>

                        <!-- Category & Tags -->
                        <div v-if="product.tags && product.tags.length > 0" class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">Categoria</h2>
                            <div class="flex flex-wrap gap-2">
                                <Link 
                                    v-for="tag in product.tags" 
                                    :key="tag"
                                    :href="route('shop.index', { tag: tag })"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-jd-accent hover:text-white transition-colors cursor-pointer"
                                >
                                    {{ tag }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Contact CTA -->
                    <div class="mt-auto">
                        <button
                            v-if="!product.sold_out"
                            @click="openContactModal"
                            class="btn-jd-primary w-full py-4 text-lg"
                        >
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Richiedi Informazioni
                        </button>

                        <!-- Information box -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-gray-700">
                                    <p class="font-medium text-gray-900 mb-1">Vuoi saperne di più?</p>
                                    <p>Contattaci per ricevere ulteriori informazioni su questo prodotto, prezzi di spedizione e disponibilità per il ritiro.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related products -->
            <div v-if="relatedProducts.length > 0" class="mt-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">Prodotti Simili</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Link 
                        v-for="relatedProduct in relatedProducts" 
                        :key="relatedProduct.id"
                        :href="route('shop.show', relatedProduct.id)"
                        class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative"
                        :class="{ 'opacity-90': relatedProduct.sold_out }"
                    >
                        <!-- Sold Out Badge -->
                        <div v-if="relatedProduct.sold_out" class="absolute top-2 left-2 z-10">
                            <div class="bg-gray-800 text-white px-3 py-1.5 rounded-lg shadow-lg font-bold text-xs">
                                VENDUTO
                            </div>
                        </div>

                        <!-- Discount Badge -->
                        <div v-if="relatedProduct.has_active_discount && !relatedProduct.sold_out" class="absolute top-2 right-2 z-10 transform rotate-12">
                            <div class="discount-badge shadow-lg text-xs px-2 py-1">
                                -{{ relatedProduct.discount_percentage }}%
                            </div>
                        </div>

                        <div class="aspect-square overflow-hidden bg-gray-200">
                            <img 
                                :src="relatedProduct.cover_image_url" 
                                :alt="relatedProduct.title"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                :class="{ 'filter grayscale opacity-60': relatedProduct.sold_out }"
                            >
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-jd truncate group-hover:text-jd-accent transition-colors">
                                {{ relatedProduct.title }}
                            </h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span v-if="relatedProduct.has_active_discount && !relatedProduct.sold_out" class="text-sm text-gray-500 line-through">
                                    €{{ relatedProduct.original_price }}
                                </span>
                                <span class="text-xl font-bold text-jd-title">
                                    €{{ relatedProduct.price }}
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Contact Modal -->
        <ContactModal 
            :show="showContactModal"
            :product="product"
            @close="showContactModal = false"
        />
    </ShopLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import ProductGallery from '@/Components/Shop/ProductGallery.vue';
import ContactModal from '@/Components/Shop/ContactModal.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    relatedProducts: {
        type: Array,
        default: () => [],
    },
});

const showContactModal = ref(false);

const openContactModal = () => {
    showContactModal.value = true;
};
</script>
