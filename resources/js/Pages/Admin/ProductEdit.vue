<template>
    <Head :title="`Modifica: ${product.title}`">
        <meta name="robots" content="noindex, nofollow">
    </Head>
    <AdminLayout>
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <Link :href="route('admin.dashboard')" class="text-blue-600 hover:text-blue-700 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Torna alla Dashboard
                        </Link>
                        <h1 class="text-3xl font-bold text-gray-900 mt-4">Modifica Prodotto</h1>
                        <p class="text-gray-600 mt-1">Aggiorna le informazioni del prodotto e gestisci le immagini</p>
                    </div>
                    <button
                        @click="showPreview"
                        type="button"
                        class="ml-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Anteprima
                    </button>
                </div>
            </div>

            <!-- Success message -->
            <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-r-lg">
                {{ $page.props.flash.success }}
            </div>

            <!-- Global Error Message -->
            <div v-if="$page.props.flash?.error" class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $page.props.flash.error }}
                </div>
            </div>

            <!-- Validation Errors Summary -->
            <div v-if="Object.keys(form.errors).length > 0" class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold">Per favore correggi i seguenti errori:</p>
                        <ul class="mt-2 list-disc list-inside">
                            <li v-for="(error, field) in form.errors" :key="field">
                                <span class="font-medium capitalize">{{ field.replace('_', ' ') }}:</span> {{ error }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Discount Information -->
            <div v-if="product.has_active_discount || product.has_future_discount" class="mb-6 bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4" :class="product.has_active_discount ? 'border-orange-500' : 'border-yellow-500'">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">
                            <span v-if="product.has_active_discount">Sconto Attivo</span>
                            <span v-else>Sconto Programmato</span>
                        </h3>
                        <div class="space-y-2 sm:space-y-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Percentuale:</span>
                                <span class="px-2 sm:px-3 py-1 bg-orange-100 text-orange-700 rounded-lg font-bold text-xs sm:text-sm">-{{ product.discount_percentage }}%</span>
                            </div>
                            <div v-if="product.original_price" class="flex flex-wrap items-center gap-2">
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Prezzo originale:</span>
                                <span class="text-gray-500 line-through text-sm sm:text-base">€{{ product.original_price }}</span>
                                <span class="text-base sm:text-lg font-bold text-orange-600">€{{ product.price }}</span>
                            </div>
                            <div v-if="product.discount_start_date" class="flex flex-wrap items-center gap-2">
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Data inizio:</span>
                                <span class="text-xs sm:text-sm text-gray-900">{{ formatDate(product.discount_start_date) }}</span>
                            </div>
                            <div v-if="product.discount_end_date" class="flex flex-wrap items-center gap-2">
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Data fine:</span>
                                <span class="text-xs sm:text-sm text-gray-900">{{ formatDate(product.discount_end_date) }}</span>
                            </div>
                            <div v-else-if="product.has_active_discount" class="text-xs sm:text-sm text-gray-600">
                                <span class="font-medium">Sconto permanente</span>
                            </div>
                        </div>
                    </div>
                    <button
                        @click="removeDiscount"
                        class="w-full sm:w-auto sm:ml-4 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium flex items-center justify-center gap-2 flex-shrink-0"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Rimuovi sconto
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Product form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Dettagli Prodotto</h2>
                    <form @submit.prevent="submit">
                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Titolo <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    id="title"
                                    v-model="form.title" 
                                    type="text" 
                                    maxlength="35"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.title }"
                                    required
                                >
                                <div class="flex justify-between mt-1">
                                    <p v-if="form.errors.title" class="text-sm text-red-600">{{ form.errors.title }}</p>
                                    <p class="text-sm text-gray-500 ml-auto">{{ form.title.length }}/35</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Descrizione
                                </label>
                                <textarea 
                                    id="description"
                                    v-model="form.description" 
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.description }"
                                ></textarea>
                                <p v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</p>
                            </div>

                            <!-- Tags -->
                            <div>
                                <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">
                                    Categorie
                                </label>
                                <input 
                                    id="tags"
                                    v-model="form.tags" 
                                    type="text" 
                                    placeholder="es: arredamento,specchio,vintage"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.tags }"
                                >
                                <p class="text-sm text-gray-500 mt-1">Separa le categorie con virgole</p>
                                <p v-if="form.errors.tags" class="text-sm text-red-600 mt-1">{{ form.errors.tags }}</p>
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Prezzo <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                                    <input 
                                        id="price"
                                        v-model="form.price" 
                                        type="number" 
                                        step="0.01"
                                        min="0"
                                        class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="{ 'border-red-500': form.errors.price }"
                                        required
                                    >
                                </div>
                                <p v-if="form.errors.price" class="text-sm text-red-600 mt-1">{{ form.errors.price }}</p>
                            </div>

                            <!-- Sold Out -->
                            <div class="flex items-center">
                                <input 
                                    id="sold_out"
                                    v-model="form.sold_out" 
                                    type="checkbox"
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="sold_out" class="ml-3 text-sm font-medium text-gray-700">
                                    Segna come venduto
                                </label>
                            </div>

                            <!-- Submit button -->
                            <div class="pt-4 border-t border-gray-200">
                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ form.processing ? 'Aggiornamento...' : 'Aggiorna Prodotto' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Image management -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Immagini Prodotto</h2>
                    
                    <!-- Image uploader -->
                    <ImageUploader 
                        :product-id="product.id"
                        @uploaded="handleImageUploaded"
                    />

                    <!-- Existing images -->
                    <div v-if="images.length > 0" class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">
                            Immagini Attuali ({{ images.length }})
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <ImagePreview
                                v-for="image in images"
                                :key="image.id"
                                :image="image"
                                @delete="handleImageDelete"
                                @set-cover="handleSetCover"
                            />
                        </div>
                    </div>

                    <div v-else class="mt-6 text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Nessuna immagine ancora</p>
                        <p class="text-xs text-gray-400">Carica delle immagini per iniziare</p>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="mt-6 bg-white rounded-lg shadow-md p-6 border-t-4 border-red-500">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-red-900">Zona Pericolosa</h3>
                </div>
                <p class="text-sm text-gray-600 mb-4">Eliminando questo prodotto, sarà irrimediabilmente cancellato. Questa azione non può essere annullata.</p>
                <button 
                    @click="showDeleteModal = true"
                    type="button"
                    class="w-full px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Elimina Prodotto
                </button>
            </div>
        </div>

        <!-- Product Preview Modal -->
        <ProductPreviewModal 
            :show="showPreviewModal" 
            :product="previewProduct"
            @close="closePreview"
        />

        <!-- Remove Discount Modal -->
        <RemoveDiscountModal
            v-if="showRemoveDiscountModal"
            :show="showRemoveDiscountModal"
            :product="product"
            @close="showRemoveDiscountModal = false"
            @confirm="handleDiscountRemoved"
        />

        <!-- Delete Confirmation Modal -->
        <div 
            v-if="showDeleteModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            @click.self="showDeleteModal = false"
        >
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Conferma Eliminazione</h3>
                </div>
                
                <p class="text-gray-700 mb-6">
                    Sei sicuro di voler eliminare <strong>"{{ product.title }}"</strong>?
                </p>
                <p class="text-sm text-red-600 mb-6">
                    Questa azione non può essere annullata. Tutte le immagini e i dati associati verranno eliminati definitivamente.
                </p>
                
                <div class="flex gap-3">
                    <button
                        @click="showDeleteModal = false"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Annulla
                    </button>
                    <button
                        @click="deleteProduct"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    >
                        Elimina Definitivamente
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link, router, Head } from '@inertiajs/vue3';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ImageUploader from '@/Components/Admin/ImageUploader.vue';
import ImagePreview from '@/Components/Admin/ImagePreview.vue';
import ProductPreviewModal from '@/Components/Admin/ProductPreviewModal.vue';
import RemoveDiscountModal from '@/Components/Admin/RemoveDiscountModal.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const images = ref([...props.product.images]);

const showPreviewModal = ref(false);
const showDeleteModal = ref(false);
const showRemoveDiscountModal = ref(false);
const previewProduct = ref({
    id: props.product.id,
    title: props.product.title,
    description: props.product.description,
    tags: props.product.tags,
    price: props.product.price,
    sold_out: props.product.sold_out,
    images: images.value,
    has_active_discount: props.product.has_active_discount,
    has_future_discount: props.product.has_future_discount,
    discount_percentage: props.product.discount_percentage,
    original_price: props.product.original_price,
    discount_end_date: props.product.discount_end_date,
});

const form = useForm({
    title: props.product.title,
    description: props.product.description,
    tags: props.product.tags,
    price: props.product.price,
    sold_out: props.product.sold_out,
});

const submit = () => {
    form.put(route('products.update', props.product.id));
};

const handleImageUploaded = (image) => {
    images.value.push(image);
};

const handleImageDelete = async (imageId) => {
    try {
        await axios.delete(route('images.delete', imageId));
        images.value = images.value.filter(img => img.id !== imageId);
    } catch (error) {
        if (error.response?.status === 404) {
            // Image already deleted, just remove from UI
            images.value = images.value.filter(img => img.id !== imageId);
        } else if (error.response?.status === 403) {
            alert('Non hai i permessi per eliminare questa immagine.');
        } else if (error.response?.status >= 500) {
            alert('Errore del server. Riprova più tardi.');
        } else {
            alert('Impossibile eliminare l\'immagine: ' + (error.response?.data?.message || error.message || 'Errore sconosciuto'));
        }
    }
};

const handleSetCover = async (imageId) => {
    try {
        await axios.patch(route('images.set-cover', imageId));
        images.value = images.value.map(img => ({
            ...img,
            is_cover: img.id === imageId,
        }));
    } catch (error) {
        alert('Impossibile impostare l\'immagine di copertina: ' + (error.response?.data?.message || error.message));
    }
};

const showPreview = () => {
    // Update preview product with current form values
    previewProduct.value = {
        id: props.product.id,
        title: form.title,
        description: form.description,
        tags: form.tags,
        price: form.price,
        sold_out: form.sold_out,
        images: images.value,
        has_active_discount: props.product.has_active_discount,
        has_future_discount: props.product.has_future_discount,
        discount_percentage: props.product.discount_percentage,
        original_price: props.product.original_price,
        discount_end_date: props.product.discount_end_date,
    };
    showPreviewModal.value = true;
};

const closePreview = () => {
    showPreviewModal.value = false;
};

const deleteProduct = () => {
    showDeleteModal.value = false;
    router.delete(route('products.destroy', props.product.id));
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('it-IT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const removeDiscount = () => {
    showRemoveDiscountModal.value = true;
};

const handleDiscountRemoved = () => {
    showRemoveDiscountModal.value = false;
    router.reload({ only: ['product'] });
};
</script>

