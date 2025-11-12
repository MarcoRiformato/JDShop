<template>
    <!-- Modal Overlay -->
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-if="show"
                class="fixed inset-0 z-50 overflow-y-auto bg-jd-base-light bg-opacity-10 backdrop-blur-md"
                @click="closeModal"
            >
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <Transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div 
                            v-if="show"
                            @click.stop
                            class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden"
                        >
                            <!-- Header -->
                            <div class="sticky top-0 z-10 bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Anteprima Cliente</h2>
                                </div>
                                <button 
                                    @click="closeModal"
                                    class="p-2 hover:bg-jd-base-light/20 rounded-lg transition-colors"
                                >
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Preview Content -->
                            <div class="p-6 md:p-8 max-h-[80vh] overflow-y-auto">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 min-w-0">
                                    <!-- Image Gallery -->
                                    <div>
                                        <div v-if="product.images && product.images.length > 0" class="space-y-4">
                                            <!-- Main Image -->
                                            <div class="aspect-square overflow-hidden rounded-xl bg-gray-200 shadow-lg">
                                                <img 
                                                    :src="selectedImage.url" 
                                                    :alt="product.title"
                                                    class="w-full h-full object-cover"
                                                >
                                            </div>

                                            <!-- Thumbnail Navigation -->
                                            <div v-if="product.images.length > 1" class="grid grid-cols-4 gap-2">
                                                <button
                                                    v-for="image in product.images"
                                                    :key="image.id"
                                                    @click="selectedImage = image"
                                                    class="aspect-square overflow-hidden rounded-lg border-2 transition-all hover:border-blue-500"
                                                    :class="selectedImage.id === image.id ? 'border-blue-600 ring-2 ring-blue-200' : 'border-gray-200'"
                                                >
                                                    <img 
                                                        :src="image.thumbnail_url" 
                                                        :alt="'Thumbnail'"
                                                        class="w-full h-full object-cover"
                                                    >
                                                </button>
                                            </div>
                                        </div>
                                        <div v-else class="aspect-square bg-gray-100 rounded-xl overflow-hidden shadow-lg flex items-center justify-center">
                                            <div class="text-center p-8">
                                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="mt-2 text-gray-500 text-xs">Nessuna immagine</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex flex-col min-w-0">
                                        <div class="min-w-0">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 break-words">
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

                                                <div class="flex flex-wrap items-center gap-4">
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
                                                <p class="text-gray-700 leading-relaxed whitespace-pre-line break-words overflow-wrap-anywhere">
                                                    {{ product.description }}
                                                </p>
                                            </div>

                                            <!-- Tags -->
                                            <div v-if="product.tags" class="mb-8">
                                                <h2 class="text-lg font-semibold text-gray-900 mb-3">Categorie</h2>
                                                <div class="flex flex-wrap gap-2">
                                                    <span
                                                        v-for="tag in getTags(product.tags)"
                                                        :key="tag"
                                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium"
                                                    >
                                                        {{ tag }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Info message -->
                                        <div class="mt-auto pt-6 border-t border-gray-200">
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                <p class="text-sm text-blue-900">
                                                    <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Così i clienti vedranno questo prodotto nel catalogo.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    product: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close']);

const selectedImage = ref(null);

const closeModal = () => {
    emit('close');
};

const getTags = (tags) => {
    if (!tags) return [];
    return tags.split(',').map(tag => tag.trim());
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear()).slice(-2);
    return `${day}/${month}/${year}`;
};

// Handle escape key
const handleEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        closeModal();
    }
};

// Select cover image or first image
watch(() => props.product, (newProduct) => {
    if (newProduct && newProduct.images && newProduct.images.length > 0) {
        const coverImage = newProduct.images.find(img => img.is_cover);
        selectedImage.value = coverImage || newProduct.images[0];
    }
}, { immediate: true });

// Lock body scroll when modal is open
watch(() => props.show, (show) => {
    if (show) {
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', handleEscape);
    } else {
        document.body.style.overflow = '';
        document.removeEventListener('keydown', handleEscape);
    }
});

onMounted(() => {
    if (props.product && props.product.images && props.product.images.length > 0) {
        const coverImage = props.product.images.find(img => img.is_cover);
        selectedImage.value = coverImage || props.product.images[0];
    }
});
</script>

