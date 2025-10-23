<template>
    <Head title="Catalogo Prodotti">
        <meta name="description" content="Scopri la nostra collezione di materiali per eventi: audio professionale, arredi vintage, scenografie, specchi e molto altro per i tuoi eventi.">
        <meta property="og:title" content="JDOutlet - Catalogo Prodotti">
        <meta property="og:description" content="Scopri la nostra collezione di materiali per eventi: audio professionale, arredi vintage, scenografie, specchi e molto altro.">
        <meta property="og:url" content="{{ route('shop.index') }}">
        <meta name="twitter:title" content="JDOutlet - Catalogo Prodotti">
        <meta name="twitter:description" content="Scopri la nostra collezione di materiali per eventi: audio professionale, arredi vintage, scenografie e molto altro.">
    </Head>
    <ShopLayout>
        <!-- Hero section -->
        <div class="bg-gradient-to-br from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Benvenuto su JDOutlet</h1>
                <p class="text-xl text-blue-100 max-w-2xl">
                    Scopri la nostra collezione di materiali per eventi: audio, arredi, scenografie e molto altro
                </p>
            </div>
        </div>

        <!-- Search and filters -->
        <div class="bg-white border-b border-gray-200 sticky top-16 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <input 
                                v-model="searchQuery"
                                @input="handleSearch"
                                type="text" 
                                placeholder="Cerca prodotti..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Tag filter -->
                    <div v-if="tags.length > 0" class="md:w-64">
                        <select 
                            v-model="selectedTag"
                            @change="handleTagFilter"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Tutte le Categorie</option>
                            <option v-for="tag in tags" :key="tag" :value="tag">
                                {{ tag }}
                            </option>
                        </select>
                    </div>

                    <!-- Clear filters -->
                    <button 
                        v-if="filters.search || filters.tag"
                        @click="clearFilters"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                    >
                        Cancella Filtri
                    </button>
                </div>
            </div>
        </div>

        <!-- Products grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <ProductCard 
                    v-for="product in products" 
                    :key="product.id" 
                    :product="product"
                />
            </div>

            <!-- Empty state -->
            <div v-else class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Nessun prodotto trovato</h3>
                <p class="mt-2 text-gray-500">
                    {{ filters.search || filters.tag ? 'Prova a modificare i filtri' : 'Torna presto per nuovi articoli!' }}
                </p>
            </div>
        </div>
    </ShopLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import ProductCard from '@/Components/Shop/ProductCard.vue';

const props = defineProps({
    products: {
        type: Array,
        required: true,
    },
    tags: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ search: '', tag: '' }),
    },
});

const searchQuery = ref(props.filters.search || '');
const selectedTag = ref(props.filters.tag || '');

let searchTimeout = null;

const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('shop.index'), {
            search: searchQuery.value,
            tag: selectedTag.value,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
};

const handleTagFilter = () => {
    router.get(route('shop.index'), {
        search: searchQuery.value,
        tag: selectedTag.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    searchQuery.value = '';
    selectedTag.value = '';
    router.get(route('shop.index'));
};
</script>

