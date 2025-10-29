<template>
    <Head :title="product.title">
        <meta name="description" :content="product.description || `${product.title} - Disponibile su JDOutlet al prezzo di €${product.price}`">
        <meta property="og:title" :content="`${product.title} - JDOutlet`">
        <meta property="og:description" :content="product.description || `${product.title} disponibile al prezzo di €${product.price}`">
        <meta property="og:image" :content="product.images.length > 0 ? product.images.find(img => img.is_cover)?.url || product.images[0].url : `https://picsum.photos/seed/${product.id}/1200/630`">
        <meta property="og:url" :content="route('shop.show', product.id)">
        <meta property="og:type" content="product">
        <meta property="product:price:amount" :content="product.price">
        <meta property="product:price:currency" content="EUR">
        <meta name="twitter:title" :content="`${product.title} - JDOutlet`">
        <meta name="twitter:description" :content="product.description || `${product.title} disponibile al prezzo di €${product.price}`">
        <meta name="twitter:image" :content="product.images.length > 0 ? product.images.find(img => img.is_cover)?.url || product.images[0].url : `https://picsum.photos/seed/${product.id}/1200/630`">
    </Head>
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
                    <ProductGallery 
                        v-if="product.images.length > 0" 
                        :images="product.images"
                        :product-id="product.id"
                        @image-deleted="handleImageDeleted"
                        @cover-updated="handleCoverUpdated"
                    />
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
                        <div class="mb-6">
                            <!-- Discount Badge -->
                            <div v-if="product.has_active_discount" class="mb-4">
                                <span class="inline-flex items-center px-4 py-2 bg-orange-100 text-orange-800 text-lg font-bold rounded-full">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    SCONTO {{ product.discount_percentage }}%
                                </span>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="flex items-center gap-3">
                                    <span v-if="product.has_active_discount" class="text-2xl text-gray-500 line-through">
                                        €{{ product.original_price }}
                                    </span>
                                    <span class="text-4xl font-bold text-gray-900">
                                        €{{ product.price }}
                                    </span>
                                </div>
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

                            <!-- Discount expiry info -->
                            <div v-if="product.has_active_discount && product.discount_end_date" class="mt-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Offerta valida fino al {{ formatDate(product.discount_end_date) }}
                            </div>
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
                    <div class="mt-auto pt-6 border-t border-gray-200 space-y-4">
                        <button 
                            @click="showContactModal = true"
                            type="button"
                            class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold"
                        >
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Chiedi maggiori informazioni
                        </button>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-900">
                                <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Clicca il pulsante sopra per inviarci una richiesta. Ti risponderemo il prima possibile!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related products section -->
            <div v-if="relatedProducts.length > 0" class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Potrebbero interessarti anche</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link
                        v-for="related in relatedProducts"
                        :key="related.id"
                        :href="route('shop.show', related.id)"
                        class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all"
                    >
                        <div class="aspect-square overflow-hidden bg-gray-200">
                            <img 
                                v-if="related.cover_image_url"
                                :src="related.cover_image_url" 
                                :alt="related.title"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                            >
                            <img 
                                v-else
                                :src="`https://picsum.photos/seed/${related.id}/400/400`" 
                                :alt="related.title"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                            >
                        </div>
                        <div class="p-4 min-w-0">
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 mb-2">
                                {{ related.title }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <span v-if="related.has_active_discount" class="text-sm text-gray-500 line-through">
                                    €{{ related.original_price }}
                                </span>
                                <span class="text-lg font-bold text-gray-900">
                                    €{{ related.price }}
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
import { Link, Head, router } from '@inertiajs/vue3';
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

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('it-IT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const handleImageDeleted = (imageId) => {
    // Reload the page to refresh product data
    router.reload({ only: ['product'] });
};

const handleCoverUpdated = (image) => {
    // Reload the page to refresh product data
    router.reload({ only: ['product'] });
};
</script>

