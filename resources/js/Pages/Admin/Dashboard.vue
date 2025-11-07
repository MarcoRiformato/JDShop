<template>
    <Head title="Dashboard Admin">
        <meta name="robots" content="noindex, nofollow">
    </Head>
    <AdminLayout>
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 sm:mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">Prodotti</h1>
                    <p class="text-sm sm:text-base text-jd mt-1">Gestisci l'inventario del catalogo</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full max-w-full overflow-hidden">
                    <!-- View Mode Toggle -->
                    <div class="hidden md:flex bg-white rounded-lg shadow-sm border border-gray-200 p-1">
                        <button
                            @click="viewMode = 'list'"
                            :class="[
                                'flex items-center px-3 py-2 rounded-md transition-all text-sm font-medium',
                                viewMode === 'list' 
                                    ? 'bg-jd-accent text-white shadow-sm' 
                                    : 'text-gray-700 hover:text-jd-accent hover:bg-gray-100'
                            ]"
                            title="Vista Lista"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <span class="hidden sm:inline">Lista</span>
                        </button>
                        <button
                            @click="viewMode = 'grid-2'"
                            :class="[
                                'flex items-center px-3 py-2 rounded-md transition-all text-sm font-medium',
                                viewMode === 'grid-2' 
                                    ? 'bg-jd-accent text-white shadow-sm' 
                                    : 'text-gray-700 hover:text-jd-accent hover:bg-gray-100'
                            ]"
                            title="Griglia 2x2"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                            </svg>
                            <span class="hidden sm:inline">2x2</span>
                        </button>
                        <button
                            @click="viewMode = 'grid-4'"
                            :class="[
                                'flex items-center px-3 py-2 rounded-md transition-all text-sm font-medium',
                                viewMode === 'grid-4' 
                                    ? 'bg-jd-accent text-white shadow-sm' 
                                    : 'text-gray-700 hover:text-jd-accent hover:bg-gray-100'
                            ]"
                            title="Griglia 4x4"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                            </svg>
                            <span class="hidden sm:inline">4x4</span>
                        </button>
                    </div>
                    <Link 
                        :href="route('products.create')" 
                        class="btn-jd-primary flex-shrink w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-3 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 font-semibold whitespace-nowrap"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nuovo Prodotto
                    </Link>
                    <button
                        @click="discountModeActive && selectedProducts.length > 0 ? showDiscountModal = true : enterDiscountMode()"
                        :disabled="discountModeActive && selectedProducts.length === 0"
                        :class="[
                            'w-full sm:w-auto flex-shrink inline-flex items-center justify-center px-4 sm:px-6 py-3 rounded-lg text-center font-semibold transition-all shadow-md hover:shadow-lg transform hover:scale-105 whitespace-nowrap',
                            discountModeActive && selectedProducts.length > 0
                                ? 'bg-red-400 text-white hover:bg-red-500'
                                : 'bg-red-400 text-white hover:bg-red-500'
                        ]"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>{{ discountModeActive && selectedProducts.length > 0 ? 'Applica Sconto' : 'Gestisci Sconti' }}</span>
                    </button>
                </div>
            </div>

            <!-- Success message -->
            <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-r-lg flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $page.props.flash.success }}
            </div>

            <!-- Discount Selection Info -->
            <div v-if="discountModeActive && products.length > 0" class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center">
                            <input
                                :id="selectAllId"
                                :checked="isAllSelected"
                                :indeterminate="isIndeterminate"
                                @change="toggleSelectAll"
                                type="checkbox"
                                class="w-4 h-4 text-jd-accent bg-white border-gray-300 rounded focus:ring-jd-accent focus:ring-2"
                            >
                            <label :for="selectAllId" class="ml-2 text-sm font-medium text-gray-700">
                                Seleziona Tutto
                            </label>
                        </div>
                        <div v-if="selectedProducts.length > 0" class="text-sm text-gray-700">
                            {{ selectedProducts.length }} {{ selectedProducts.length === 1 ? 'prodotto selezionato' : 'prodotti selezionati' }}
                        </div>
                    </div>
                    <button
                        @click="exitDiscountMode"
                        class="inline-flex items-center px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Annulla
                    </button>
                </div>
            </div>


            <!-- Products table/cards -->
            <ProductTable 
                :products="products"
                :view-mode="viewMode"
                :selected-products="selectedProducts"
                :discount-mode-active="discountModeActive"
                @selection-change="handleSelectionChange"
                @remove-discount="handleEditDiscount"
            />

            <!-- Discount Modal -->
            <DiscountModal
                :show="showDiscountModal"
                :selected-products="selectedProducts"
                @close="showDiscountModal = false"
                @applied="handleDiscountApplied"
            />

            <!-- Remove Discount Modal -->
            <RemoveDiscountModal
                :show="showRemoveDiscountModal"
                :product="selectedProductForRemoval"
                @close="showRemoveDiscountModal = false"
                @confirm="handleDiscountRemoved"
            />

            <!-- Empty state -->
            <div v-if="products.length === 0" class="text-center py-16 bg-white rounded-lg shadow-sm">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Nessun prodotto ancora</h3>
                <p class="mt-2 text-jd">Inizia creando il tuo primo prodotto</p>
                <div class="mt-6">
                    <Link 
                        :href="route('products.create')" 
                        class="btn-jd-primary inline-flex items-center px-6 py-3 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 font-semibold"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crea il Primo Prodotto
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ProductTable from '@/Components/Admin/ProductTable.vue';
import DiscountModal from '@/Components/Admin/DiscountModal.vue';
import RemoveDiscountModal from '@/Components/Admin/RemoveDiscountModal.vue';

const props = defineProps({
    products: {
        type: Array,
        required: true,
    },
});

const viewMode = ref('list');
const selectedProducts = ref([]);
const showDiscountModal = ref(false);
const discountModeActive = ref(false);
const showRemoveDiscountModal = ref(false);
const selectedProductForRemoval = ref(null);

// Computed properties for select all functionality
const isAllSelected = computed(() => {
    return props.products.length > 0 && selectedProducts.value.length === props.products.length;
});

const isIndeterminate = computed(() => {
    return selectedProducts.value.length > 0 && selectedProducts.value.length < props.products.length;
});

const selectAllId = 'select-all-products';

// Methods
const toggleSelectAll = () => {
    if (isAllSelected.value) {
        selectedProducts.value = [];
    } else {
        selectedProducts.value = [...props.products];
    }
};

const handleSelectionChange = (newSelection) => {
    selectedProducts.value = newSelection;
};

const handleDiscountApplied = (result) => {
    exitDiscountMode();
};

const handleEditDiscount = (product) => {
    selectedProductForRemoval.value = product;
    showRemoveDiscountModal.value = true;
};

const handleDiscountRemoved = (result) => {
    showRemoveDiscountModal.value = false;
    selectedProductForRemoval.value = null;
};

const enterDiscountMode = () => {
    discountModeActive.value = true;
};

const exitDiscountMode = () => {
    discountModeActive.value = false;
    selectedProducts.value = [];
    showDiscountModal.value = false;
};

// Load view mode from localStorage
onMounted(() => {
    const savedViewMode = localStorage.getItem('admin_view_mode');
    if (savedViewMode) {
        viewMode.value = savedViewMode;
    }
});

// Save view mode to localStorage
watch(viewMode, (newMode) => {
    localStorage.setItem('admin_view_mode', newMode);
});
</script>

