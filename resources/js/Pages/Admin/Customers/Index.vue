<template>
    <Head title="Clienti">
        <meta name="robots" content="noindex, nofollow">
    </Head>
    <AdminLayout>
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 sm:mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">Clienti</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Gestisci i contatti e le richieste dei clienti</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full max-w-full overflow-hidden">
                    <Link 
                        :href="route('customers.create')" 
                        class="flex-shrink w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg transform hover:scale-105 font-semibold whitespace-nowrap"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nuovo Cliente
                    </Link>
                    <button
                        v-if="!selectionMode"
                        @click="enterSelectionMode"
                        class="flex-shrink w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105 font-semibold whitespace-nowrap"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Unisci Clienti
                    </button>
                    <div v-if="selectionMode" class="flex gap-2 items-center">
                        <button
                            @click="goToMerge"
                            :disabled="selectedCustomers.length < 2"
                            class="flex-shrink inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all shadow-md hover:shadow-lg font-semibold whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Unisci ({{ selectedCustomers.length }})
                        </button>
                        <button
                            @click="exitSelectionMode"
                            class="flex-shrink inline-flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all shadow-md hover:shadow-lg font-semibold"
                        >
                            Annulla
                        </button>
                    </div>
                    <a 
                        :href="route('customers.export', filters)"
                        class="flex-shrink w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105 font-semibold whitespace-nowrap"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Esporta CSV
                    </a>
                </div>
            </div>

            <!-- Success message -->
            <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-r-lg flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $page.props.flash.success }}
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1 relative">
                        <input 
                            type="text"
                            v-model="searchQuery"
                            @input="performLiveSearch"
                            placeholder="Cerca per nome, email o telefono..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    
                    <!-- GDPR Filter -->
                    <div class="w-full sm:w-48">
                        <select 
                            v-model="gdprFilter"
                            @change="applyFilters"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Tutti i clienti</option>
                            <option value="with">Con consenso GDPR</option>
                            <option value="without">Senza consenso GDPR</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 hidden md:table-header-group">
                            <tr>
                                <th v-if="selectionMode" class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input 
                                        type="checkbox"
                                        @change="toggleAllCustomers"
                                        :checked="allCustomersSelected"
                                        class="h-4 w-4 text-purple-600 rounded"
                                    >
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                    Contatti
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Richieste
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">
                                    GDPR
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                    Ultimo Contatto
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr 
                                v-for="customer in customers" 
                                :key="customer.id"
                                class="hover:bg-gray-50"
                                :class="{ 'bg-purple-50': selectionMode && selectedCustomers.includes(customer.id) }"
                                @click="handleRowClick(customer.id)"
                            >
                                <td v-if="selectionMode" class="px-3 md:px-6 py-4" @click.stop>
                                    <input 
                                        type="checkbox"
                                        :checked="selectedCustomers.includes(customer.id)"
                                        @change="toggleCustomer(customer.id)"
                                        class="h-4 w-4 text-purple-600 rounded"
                                    >
                                </td>
                                <td class="px-3 md:px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ customer.name }}</div>
                                        <div class="text-sm text-gray-500">{{ customer.primary_email }}</div>
                                        <div class="md:hidden text-xs text-gray-400 mt-1">
                                            Richieste: {{ customer.total_inquiries }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 md:px-6 py-4 hidden lg:table-cell">
                                    <div class="text-sm text-gray-900">
                                        <div v-if="customer.phone">{{ customer.phone }}</div>
                                        <div v-else class="text-gray-400">-</div>
                                    </div>
                                </td>
                                <td class="px-3 md:px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ customer.total_inquiries }}
                                    </span>
                                </td>
                                <td class="px-3 md:px-6 py-4 hidden xl:table-cell">
                                    <span 
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            customer.gdpr_consent 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-red-100 text-red-800'
                                        ]"
                                    >
                                        {{ customer.gdpr_consent ? 'Consenso' : 'Nessun consenso' }}
                                    </span>
                                </td>
                                <td class="px-3 md:px-6 py-4 hidden lg:table-cell text-sm text-gray-500">
                                    {{ customer.last_contact_at || '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty state -->
                <div v-if="customers.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nessun cliente trovato</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ searchQuery || gdprFilter ? 'Prova a modificare i filtri di ricerca.' : 'Inizia creando il primo cliente.' }}
                    </p>
                    <div v-if="!searchQuery && !gdprFilter" class="mt-6">
                        <Link 
                            :href="route('customers.create')"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        >
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nuovo Cliente
                        </Link>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    customers: {
        type: Array,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const searchQuery = ref(props.filters.search || '');
const gdprFilter = ref(props.filters.gdpr_filter || '');
const selectedCustomers = ref([]);
const selectionMode = ref(false);
let searchTimeout = null;

const filters = computed(() => ({
    search: searchQuery.value,
    gdpr_filter: gdprFilter.value,
}));

const performLiveSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
};

const applyFilters = () => {
    router.get(route('customers.index'), filters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const toggleCustomer = (customerId) => {
    const index = selectedCustomers.value.indexOf(customerId);
    if (index > -1) {
        selectedCustomers.value.splice(index, 1);
    } else {
        selectedCustomers.value.push(customerId);
    }
};

const handleRowClick = (customerId) => {
    if (selectionMode.value) {
        toggleCustomer(customerId);
    } else {
        goToCustomer(customerId);
    }
};

const toggleAllCustomers = (event) => {
    if (event.target.checked) {
        selectedCustomers.value = props.customers.map(c => c.id);
    } else {
        selectedCustomers.value = [];
    }
};

const allCustomersSelected = computed(() => {
    return props.customers.length > 0 && selectedCustomers.value.length === props.customers.length;
});

const goToCustomer = (customerId) => {
    router.visit(route('customers.show', customerId));
};

const enterSelectionMode = () => {
    selectionMode.value = true;
    selectedCustomers.value = [];
};

const exitSelectionMode = () => {
    selectionMode.value = false;
    selectedCustomers.value = [];
};

const goToMerge = () => {
    if (selectedCustomers.value.length >= 2) {
        router.visit(route('customers.merge.show', { customers: selectedCustomers.value }));
    }
};
</script>



