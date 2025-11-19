<template>
    <Head title="Catalogo Prodotti">
        <meta name="description" content="Scopri la nostra collezione di materiali per eventi: audio professionale, arredi vintage, scenografie, specchi e molto altro per i tuoi eventi.">
        <meta property="og:title" content="JDOutlet - Catalogo Prodotti">
        <meta property="og:description" content="Scopri la nostra collezione di materiali per eventi: audio professionale, arredi vintage, scenografie, specchi e molto altro.">
        <meta property="og:url" content="{{ route('shop.index') }}">
        <meta name="twitter:title" content="JDOutlet - Catalogo Prodotti">
        <meta name="twitter:description" content="Scopri la nostra collezione di materiali per eventi: audio professionale, arredi vintage, scenografie e molto altro.">
    </Head>
    <ShopLayout :tags="tags">
        <!-- Hero Section -->
        <div class="relative h-[488px] sm:h-[525px] overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img 
                    src="/images/home_cropped.webp" 
                    alt="JDOutlet Warehouse" 
                    class="w-full h-full object-cover"
                />
                <!-- Gradient Overlay - Dark blue gradient from left to right -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#092133] from-30% via-[#092133]/35 via-60% to-transparent"></div>
            </div>

            <!-- Content -->
            <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center py-10 sm:py-12">
                <div class="max-w-2xl">
                    <!-- Main Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-bold text-white mb-5 leading-[1.2]">
                        Diamo una seconda<br>vita ai nostri materiali
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="text-base sm:text-lg text-white/95 mb-8 leading-relaxed max-w-xl">
                        Il nostro magazzino è in continua evoluzione, ma i<br class="hidden sm:block">
                        nostri materiali non perdono valore: possono<br class="hidden sm:block">
                        diventare perfetti per nuovi spazi e nuove persone.
                    </p>
                    
                    <!-- CTA Button -->
                    <Link 
                        :href="route('shop.philosophy')" 
                        class="bg-jd-accent hover:bg-[#e63f1f] text-white font-semibold px-7 py-3.5 rounded-md transition-all inline-flex items-center text-base shadow-lg hover:shadow-xl"
                    >
                        Scopri di più
                    </Link>
                </div>
            </div>
        </div>

        <!-- Search and Category Filters -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch">
                    <!-- Search -->
                    <div class="flex-1">
                        <input 
                            v-model="searchQuery"
                            @input="handleSearch"
                            type="text" 
                            placeholder="Cerca prodotti"
                            class="w-full px-5 py-3.5 text-base bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-jd-accent focus:border-jd-accent focus:outline-none transition-all"
                        >
                    </div>

                    <!-- Tag filter -->
                    <div class="sm:w-72">
                        <select 
                            v-model="selectedTag"
                            @change="handleTagFilter"
                            class="w-full px-5 py-3.5 text-base bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-jd-accent focus:border-jd-accent focus:outline-none appearance-none cursor-pointer transition-all pr-10"
                            style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3E%3Cpath stroke=%27%236b7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27m6 8 4 4 4-4%27/%3E%3C/svg%3E'); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;"
                        >
                            <option value="">Tutte le Categorie</option>
                            <option v-for="tag in tags" :key="tag" :value="tag">
                                {{ tag }}
                            </option>
                        </select>
                    </div>

                    <!-- Clear filters button if filters active -->
                    <button 
                        v-if="filters.search || filters.tag"
                        @click="clearFilters"
                        class="px-6 py-3.5 text-base link-jd hover:bg-gray-50 rounded-lg transition-colors border border-gray-300"
                    >
                        Cancella Filtri
                    </button>
                </div>
            </div>
        </div>

        <!-- Featured Products Section Header -->
        <div id="products" class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h2 class="text-3xl font-bold text-jd-section mb-2">Prodotti in evidenza</h2>
                <p class="text-jd text-base">Una selezione degli arredi disponibili in questo momento. Aggiorniamo spesso i prodotti!</p>
            </div>
        </div>

        <!-- Products grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
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
                <h3 class="mt-4 text-lg font-medium text-gray-900">
                    {{ filters.search || filters.tag ? 'Nessun prodotto trovato' : 'Stiamo lavorando per voi!' }}
                </h3>
                <p class="mt-2 text-jd">
                    {{ filters.search || filters.tag ? 'Prova a modificare i filtri di ricerca' : 'Stiamo aggiungendo nuovi prodotti. Torna presto a trovarci!' }}
                </p>
            </div>
        </div>
    </ShopLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Head, Link } from '@inertiajs/vue3';
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

