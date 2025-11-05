<template>
    <Head :title="`Cliente: ${customer.name}`">
        <meta name="robots" content="noindex, nofollow">
    </Head>
    <AdminLayout>
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back button -->
            <div class="mb-4">
                <Link :href="route('customers.index')" class="link-jd hover:link-jd-dark flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Torna ai Clienti
                </Link>
            </div>

            <!-- Success message -->
            <div v-if="$page.props.flash?.success" class="mb-6 bg-green-900 border-l-4 border-green-400 text-green-100 px-4 py-3 rounded-r-lg flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $page.props.flash.success }}
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-300 p-6 mb-6">
                        <div class="mb-6">
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ customer.name }}</h1>
                            <p class="text-gray-700 mt-1">{{ customer.primary_email }}</p>
                        </div>

                        <!-- Customer Info -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Telefono</label>
                                <p class="mt-1 text-sm text-gray-900">{{ customer.phone || '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">GDPR Consenso</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ customer.gdpr_consent ? 'Sì' : 'No' }}
                                    <span v-if="customer.gdpr_consent && customer.gdpr_consent_date" class="text-gray-500">
                                        ({{ customer.gdpr_consent_date }})
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Ultimo Contatto</label>
                                <p class="mt-1 text-sm text-gray-900">{{ customer.last_contact_at || '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Cliente dal</label>
                                <p class="mt-1 text-sm text-gray-900">{{ customer.created_at }}</p>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div v-if="customer.notes">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Note</label>
                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ customer.notes }}</p>
                        </div>

                        <!-- Email Aliases -->
                        <div v-if="customer.email_aliases.length > 0" class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Email Alternative</label>
                            <div class="flex flex-wrap gap-2">
                                <span 
                                    v-for="alias in customer.email_aliases" 
                                    :key="alias"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-700"
                                >
                                    {{ alias }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-300 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Cronologia Richieste</h2>
                        <CustomerTimeline :inquiries="inquiries" :current-customer-email="customer.primary_email" />
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-300 p-6 sticky top-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Azioni Rapide</h3>
                            <div class="space-y-3">
                                <Link 
                                    :href="route('customers.edit', customer.id)"
                                    class="btn-jd-accent w-full"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Modifica Cliente
                                </Link>
                                <Link 
                                    :href="route('customers.export', { search: customer.primary_email })"
                                    class="flex items-center justify-center w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Esporta Dati
                                </Link>
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div class="bg-white rounded-lg shadow-sm border-2 border-red-400 p-6">
                            <h3 class="text-lg font-semibold text-red-400 mb-2">Zona Pericolosa</h3>
                            <p class="text-sm text-gray-700 mb-4">
                                Eliminare un cliente rimuoverà definitivamente tutti i dati associati. Questa azione non può essere annullata.
                            </p>
                            <button
                                @click="showDeleteConfirm = true"
                                class="flex items-center justify-center w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Elimina Cliente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div 
            v-if="showDeleteConfirm"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
            @click.self="showDeleteConfirm = false"
        >
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900">Elimina Cliente</h3>
                </div>
                <p class="text-sm text-gray-500 mb-6">
                    Sei sicuro di voler eliminare <strong>{{ customer.name }}</strong>? Questa azione eliminerà anche tutte le {{ customer.total_inquiries }} richieste associate e non può essere annullata.
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        @click="showDeleteConfirm = false"
                        class="px-4 py-2 text-gray-700 bg-gray-50 rounded-lg hover:bg-whiteer transition-colors font-medium"
                    >
                        Annulla
                    </button>
                    <form @submit.prevent="deleteCustomer" class="inline">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                        >
                            Elimina
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import CustomerTimeline from '@/Components/Admin/CustomerTimeline.vue';

const props = defineProps({
    customer: {
        type: Object,
        required: true,
    },
    inquiries: {
        type: Array,
        required: true,
    },
});

const showDeleteConfirm = ref(false);

const deleteCustomer = () => {
    router.delete(route('customers.destroy', props.customer.id), {
        onSuccess: () => {
            router.visit(route('customers.index'));
        },
    });
};
</script>

