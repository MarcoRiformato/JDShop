<template>
    <Head title="Crea Cliente">
        <meta name="robots" content="noindex, nofollow">
    </Head>
    <AdminLayout>
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <Link :href="route('customers.index')" class="text-jd-accent hover:text-jd-accent-dark flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Torna ai Clienti
                </Link>
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Crea Cliente</h1>
                <p class="text-gray-700 mt-1">Aggiungi un nuovo cliente manualmente</p>
            </div>

            <!-- Validation Errors -->
            <div v-if="Object.keys(form.errors).length > 0" class="mb-6 bg-red-900 border-l-4 border-red-400 text-red-100 px-4 py-3 rounded-r-lg">
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

            <div class="bg-jd-base-light rounded-lg shadow-md p-6">
                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nome <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="name"
                                v-model="form.name" 
                                type="text" 
                                class="input-jd w-full"
                                :class="{ 'border-red-500': form.errors.name }"
                                required
                            >
                            <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="primary_email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="primary_email"
                                v-model="form.primary_email" 
                                type="email" 
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                class="input-jd w-full"
                                :class="{ 'border-red-500': form.errors.primary_email }"
                                required
                            >
                            <p v-if="form.errors.primary_email" class="text-sm text-red-600 mt-1">{{ form.errors.primary_email }}</p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Telefono
                            </label>
                            <input 
                                id="phone"
                                v-model="form.phone" 
                                type="tel" 
                                pattern="[+]?[0-9\s\-\(\)]{0,20}"
                                class="input-jd w-full"
                                :class="{ 'border-red-500': form.errors.phone }"
                            >
                            <p class="text-xs text-jd-muted mt-1">Formato: +39 123 456 7890</p>
                            <p v-if="form.errors.phone" class="text-sm text-red-600 mt-1">{{ form.errors.phone }}</p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Note
                            </label>
                            <textarea 
                                id="notes"
                                v-model="form.notes" 
                                rows="4"
                                class="input-jd w-full"
                                :class="{ 'border-red-500': form.errors.notes }"
                            ></textarea>
                            <p v-if="form.errors.notes" class="text-sm text-red-600 mt-1">{{ form.errors.notes }}</p>
                        </div>

                        <!-- GDPR Consent -->
                        <div class="bg-gray-50 border border-gray-300 rounded-lg p-4">
                            <label class="flex items-start">
                                <input 
                                    id="gdpr_consent"
                                    v-model="form.gdpr_consent"
                                    type="checkbox"
                                    class="mt-1 mr-3 h-4 w-4 text-jd-accent focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <span class="text-sm text-gray-700">
                                    Il cliente ha fornito il consenso GDPR
                                </span>
                            </label>
                            <p v-if="form.errors.gdpr_consent" class="text-sm text-red-600 mt-1 ml-7">{{ form.errors.gdpr_consent }}</p>
                        </div>

                        <!-- Submit buttons -->
                        <div class="flex gap-3 pt-4 border-t border-gray-300">
                            <Link 
                                :href="route('customers.index')"
                                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-center"
                            >
                                Annulla
                            </Link>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed font-semibold shadow-lg"
                            >
                                <span v-if="form.processing" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Creazione...
                                </span>
                                <span v-else>Crea Cliente</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    primary_email: '',
    phone: '',
    notes: '',
    gdpr_consent: false,
});

const submit = () => {
    form.post(route('customers.store'));
};
</script>



