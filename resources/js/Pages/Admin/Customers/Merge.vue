<template>
    <Head title="Unisci Clienti">
        <meta name="robots" content="noindex, nofollow">
    </Head>
    <AdminLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <Link :href="route('customers.index')" class="text-blue-600 hover:text-blue-700 flex items-center mb-4 text-sm">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Torna ai Clienti
                </Link>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Unisci Clienti</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Seleziona i dati corretti da mantenere</p>
            </div>

            <!-- Validation Errors -->
            <div v-if="Object.keys(form.errors).length > 0" class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold">Per favore correggi i seguenti errori:</p>
                        <ul class="mt-2 list-disc list-inside">
                            <li v-for="(error, field) in form.errors" :key="field">
                                {{ error }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm text-yellow-800">
                        Verranno uniti <strong>{{ customers.length }}</strong> clienti. Tutti i dati verranno combinati e i clienti secondari saranno eliminati.
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
                <!-- Primary Customer Selection -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-3">Cliente Principale</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        Seleziona quale cliente mantenere come principale
                    </p>
                    <div class="space-y-2">
                        <label 
                            v-for="customer in customers" 
                            :key="customer.id"
                            class="flex items-start p-3 sm:p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                            :class="form.primary_customer_id == customer.id ? 'border-purple-500 bg-purple-50' : 'border-gray-200'"
                        >
                            <input 
                                type="radio"
                                :value="customer.id"
                                v-model="form.primary_customer_id"
                                class="mt-1 mr-3 h-4 w-4 text-purple-600"
                            >
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ customer.name }}</p>
                                <p class="text-sm text-gray-600 truncate">{{ customer.primary_email }}</p>
                                <p v-if="customer.phone" class="text-sm text-gray-500 truncate">{{ customer.phone }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ customer.total_inquiries }} richieste</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Final Data Selection -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Dati Finali</h2>
                    
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nome <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <select 
                                    v-if="!showManualName"
                                    v-model="form.final_data.name"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                    required
                                    @change="form.final_data.name = form.final_data.name"
                                >
                                    <option :value="null" disabled>Seleziona un nome</option>
                                    <option v-for="customer in customers" :key="customer.id" :value="customer.name">
                                        {{ customer.name }}
                                    </option>
                                </select>
                                <div v-if="showManualName" class="relative">
                                    <input 
                                        type="text"
                                        v-model="form.final_data.name"
                                        placeholder="Scrivi manualmente qui"
                                        class="w-full px-3 sm:px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base bg-blue-50"
                                        required
                                    >
                                    <button
                                        @click.prevent="showManualName = false"
                                        type="button"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <button
                                    v-if="!showManualName"
                                    @click.prevent="showManualName = true"
                                    type="button"
                                    class="text-sm text-blue-600 hover:text-blue-700 flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    Oppure scrivi manualmente
                                </button>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <select 
                                    v-if="!showManualEmail"
                                    v-model="form.final_data.primary_email"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                    required
                                    @change="form.final_data.primary_email = form.final_data.primary_email"
                                >
                                    <option :value="null" disabled>Seleziona un'email</option>
                                    <option v-for="customer in customers" :key="customer.id" :value="customer.primary_email">
                                        {{ customer.primary_email }}
                                    </option>
                                </select>
                                <div v-if="showManualEmail" class="relative">
                                    <input 
                                        type="email"
                                        v-model="form.final_data.primary_email"
                                        placeholder="Scrivi manualmente qui"
                                        class="w-full px-3 sm:px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base bg-blue-50"
                                        required
                                    >
                                    <button
                                        @click.prevent="showManualEmail = false"
                                        type="button"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <button
                                    v-if="!showManualEmail"
                                    @click.prevent="showManualEmail = true"
                                    type="button"
                                    class="text-sm text-blue-600 hover:text-blue-700 flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    Oppure scrivi manualmente
                                </button>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telefono
                            </label>
                            <div class="space-y-2">
                                <select 
                                    v-if="!showManualPhone"
                                    v-model="form.final_data.phone"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                    @change="form.final_data.phone = form.final_data.phone"
                                >
                                    <option :value="null" disabled>Seleziona un telefono</option>
                                    <option v-if="customers.filter(c => !c.phone).length > 0" :value="''">Nessun telefono</option>
                                    <option v-for="customer in customers.filter(c => c.phone)" :key="customer.id" :value="customer.phone">
                                        {{ customer.phone }}
                                    </option>
                                </select>
                                <div v-if="showManualPhone" class="relative">
                                    <input 
                                        type="tel"
                                        v-model="form.final_data.phone"
                                        placeholder="Scrivi manualmente qui"
                                        class="w-full px-3 sm:px-4 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base bg-blue-50"
                                    >
                                    <button
                                        @click.prevent="showManualPhone = false"
                                        type="button"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <button
                                    v-if="!showManualPhone"
                                    @click.prevent="showManualPhone = true"
                                    type="button"
                                    class="text-sm text-blue-600 hover:text-blue-700 flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    Oppure scrivi manualmente
                                </button>
                            </div>
                        </div>


                        <!-- GDPR -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                GDPR Consenso
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox"
                                        v-model="form.final_data.gdpr_consent"
                                        class="mr-2 h-4 w-4 text-blue-600"
                                    >
                                    <span class="text-sm text-gray-700">Cliente ha fornito consenso GDPR</span>
                                </label>
                                <input 
                                    type="datetime-local"
                                    v-model="form.final_data.gdpr_consent_date"
                                    v-if="form.final_data.gdpr_consent"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <Link 
                        :href="route('customers.index')"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-center order-2 sm:order-1"
                    >
                        Annulla
                    </Link>
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed font-semibold shadow-lg order-1 sm:order-2"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Unione...
                        </span>
                        <span v-else>Unisci Clienti</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    customers: {
        type: Array,
        required: true,
    },
});

const primaryCustomer = ref(null);
const showManualName = ref(false);
const showManualEmail = ref(false);
const showManualPhone = ref(false);

// Set primary customer to first one by default
if (props.customers.length > 0) {
    primaryCustomer.value = props.customers[0];
}

const form = useForm({
    primary_customer_id: props.customers[0]?.id || null,
    secondary_customer_ids: props.customers.slice(1).map(c => c.id), // All except first as secondary
    final_data: {
        name: props.customers[0]?.name || '',
        primary_email: props.customers[0]?.primary_email || '',
        phone: props.customers[0]?.phone || '',
        gdpr_consent: props.customers[0]?.gdpr_consent || false,
        gdpr_consent_date: props.customers[0]?.gdpr_consent_date || '',
    },
});

// Watch for primary customer changes to update default values and secondary IDs
watch(() => form.primary_customer_id, (newId) => {
    if (newId) {
        const selected = props.customers.find(c => c.id === newId);
        if (selected) {
            if (!form.final_data.name) form.final_data.name = selected.name;
            if (!form.final_data.primary_email) form.final_data.primary_email = selected.primary_email;
            if (!form.final_data.phone) form.final_data.phone = selected.phone;
        }
        // Update secondary customer IDs to exclude the new primary
        form.secondary_customer_ids = props.customers.filter(c => c.id !== newId).map(c => c.id);
    }
});

const submit = () => {
    console.log('========== STARTING MERGE ==========');
    console.log('Primary customer ID:', form.primary_customer_id);
    console.log('Secondary customer IDs:', form.secondary_customer_ids);
    console.log('Final data:', JSON.stringify(form.final_data, null, 2));
    
    form.post(route('customers.merge'), {
        onSuccess: (page) => {
            console.log('========== MERGE SUCCESS ==========');
            console.log('Page component:', page.component);
            console.log('Page URL:', page.url);
            console.log('Flash messages:', page.props?.flash);
            console.log('Customers in response:', page.props?.customers?.length);
            
            if (page.props?.flash?.error) {
                console.error('ERROR IN SUCCESS:', page.props.flash.error);
            }
            
            if (page.props?.flash?.success) {
                console.log('SUCCESS MESSAGE:', page.props.flash.success);
            }
        },
        onError: (errors) => {
            console.error('========== MERGE ERRORS ==========');
            console.error('Errors object:', errors);
            console.error('Errors keys:', Object.keys(errors));
        },
        onFinish: () => {
            console.log('========== REQUEST FINISHED ==========');
        }
    });
};
</script>

