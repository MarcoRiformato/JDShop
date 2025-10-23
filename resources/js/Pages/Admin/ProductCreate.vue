<template>
    <Head title="Crea Prodotto">
        <meta name="robots" content="noindex, nofollow">
    </Head>
    <AdminLayout>
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <Link :href="route('admin.dashboard')" class="text-blue-600 hover:text-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Torna alla Dashboard
                </Link>
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Crea Prodotto</h1>
                <p class="text-gray-600 mt-1">Aggiungi un nuovo prodotto al catalogo</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form @submit.prevent="submit">
                    <div class="space-y-6">
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

                        <!-- Image info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-blue-900">
                                    Puoi aggiungere immagini al prodotto dopo averlo creato, nella pagina di modifica.
                                </p>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <Link 
                                :href="route('admin.dashboard')" 
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                Annulla
                            </Link>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ form.processing ? 'Creazione...' : 'Crea Prodotto' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({
    title: '',
    description: '',
    tags: '',
    price: '',
    sold_out: false,
});

const submit = () => {
    form.post(route('products.store'));
};
</script>

