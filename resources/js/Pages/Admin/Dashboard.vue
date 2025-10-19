<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Equipment Inventory</h1>
                    <p class="text-gray-600 mt-1">Manage event equipment, props, and furniture</p>
                </div>
                <Link 
                    :href="route('products.create')" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Product
                </Link>
            </div>

            <!-- Success message -->
            <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ $page.props.flash.success }}
            </div>

            <!-- Products table/cards -->
            <ProductTable 
                :products="products" 
                @delete="confirmDelete"
            />

            <!-- Empty state -->
            <div v-if="products.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No equipment listed</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding your first item.</p>
                <div class="mt-6">
                    <Link 
                        :href="route('products.create')" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Product
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ProductTable from '@/Components/Admin/ProductTable.vue';

defineProps({
    products: {
        type: Array,
        required: true,
    },
});

const confirmDelete = (productId) => {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        router.delete(route('products.destroy', productId));
    }
};
</script>

