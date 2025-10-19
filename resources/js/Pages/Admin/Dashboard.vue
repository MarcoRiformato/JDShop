<template>
    <AdminLayout>
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Products</h1>
                    <p class="text-gray-600 mt-1">Manage your shop inventory</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <!-- View Mode Toggle -->
                    <div class="flex bg-white rounded-lg shadow-sm border border-gray-200 p-1">
                        <button
                            @click="viewMode = 'list'"
                            :class="[
                                'flex items-center px-3 py-2 rounded-md transition-all text-sm font-medium',
                                viewMode === 'list' 
                                    ? 'bg-blue-600 text-white shadow-sm' 
                                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
                            ]"
                            title="List View"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <span class="hidden sm:inline">List</span>
                        </button>
                        <button
                            @click="viewMode = 'grid-2'"
                            :class="[
                                'flex items-center px-3 py-2 rounded-md transition-all text-sm font-medium',
                                viewMode === 'grid-2' 
                                    ? 'bg-blue-600 text-white shadow-sm' 
                                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
                            ]"
                            title="2x2 Grid"
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
                                    ? 'bg-blue-600 text-white shadow-sm' 
                                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
                            ]"
                            title="4x4 Grid"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                            </svg>
                            <span class="hidden sm:inline">4x4</span>
                        </button>
                    </div>
                    <Link 
                        :href="route('products.create')" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg transform hover:scale-105 font-semibold whitespace-nowrap"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Product
                    </Link>
                </div>
            </div>

            <!-- Success message -->
            <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-r-lg flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $page.props.flash.success }}
            </div>

            <!-- Info banner -->
            <div v-if="products.length > 0" class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-900">
                    <p class="font-semibold mb-1">Click on any product to preview how customers will see it</p>
                    <p class="text-blue-700">You can edit or delete products using the action buttons</p>
                </div>
            </div>

            <!-- Products table/cards -->
            <ProductTable 
                :products="products"
                :view-mode="viewMode"
                @delete="confirmDelete"
                @preview="showPreview"
            />

            <!-- Empty state -->
            <div v-if="products.length === 0" class="text-center py-16 bg-white rounded-lg shadow-sm">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">No products yet</h3>
                <p class="mt-2 text-gray-600">Get started by creating your first product</p>
                <div class="mt-6">
                    <Link 
                        :href="route('products.create')" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg transform hover:scale-105 font-semibold"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Your First Product
                    </Link>
                </div>
            </div>
        </div>

        <!-- Product Preview Modal -->
        <ProductPreviewModal 
            :show="showPreviewModal" 
            :product="previewProduct"
            @close="closePreview"
        />
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ProductTable from '@/Components/Admin/ProductTable.vue';
import ProductPreviewModal from '@/Components/Admin/ProductPreviewModal.vue';

defineProps({
    products: {
        type: Array,
        required: true,
    },
});

const viewMode = ref('list');
const showPreviewModal = ref(false);
const previewProduct = ref({
    id: null,
    title: '',
    description: '',
    tags: '',
    price: 0,
    sold_out: false,
    images: [],
});

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

const confirmDelete = (productId) => {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        router.delete(route('products.destroy', productId));
    }
};

const showPreview = async (productId) => {
    try {
        // Fetch full product details with images
        const response = await axios.get(route('products.show', productId));
        
        previewProduct.value = response.data.product;
        showPreviewModal.value = true;
    } catch (error) {
        console.error('Failed to load product preview:', error);
        alert('Failed to load product preview. Please try again.');
    }
};

const closePreview = () => {
    showPreviewModal.value = false;
};
</script>

