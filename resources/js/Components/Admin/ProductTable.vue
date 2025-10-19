<template>
    <div>
        <!-- Desktop table view -->
        <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tags
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <img 
                                        :src="product.cover_image_url || `https://picsum.photos/seed/${product.id}/100/100`" 
                                        :alt="product.title"
                                        class="h-12 w-12 rounded-lg object-cover"
                                    >
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ product.title }}</div>
                                    <div class="text-sm text-gray-500">{{ product.images_count }} images</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ product.tags || 'No tags' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${{ product.price }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span 
                                :class="[
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    product.sold_out ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
                                ]"
                            >
                                {{ product.sold_out ? 'Sold Out' : 'Available' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <Link 
                                :href="route('products.edit', product.id)" 
                                class="text-blue-600 hover:text-blue-900 mr-4"
                            >
                                Edit
                            </Link>
                            <button 
                                @click="$emit('delete', product.id)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile card view -->
        <div class="md:hidden space-y-4">
            <div 
                v-for="product in products" 
                :key="product.id" 
                class="bg-white rounded-lg shadow p-4"
            >
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <img 
                            :src="product.cover_image_url || `https://picsum.photos/seed/${product.id}/200/200`" 
                            :alt="product.title"
                            class="h-20 w-20 rounded-lg object-cover"
                        >
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ product.title }}</h3>
                        <p class="text-sm text-gray-500">{{ product.tags || 'No tags' }}</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">${{ product.price }}</p>
                        <span 
                            :class="[
                                'inline-block px-2 py-1 text-xs font-semibold rounded-full mt-2',
                                product.sold_out ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
                            ]"
                        >
                            {{ product.sold_out ? 'Sold Out' : 'Available' }}
                        </span>
                    </div>
                </div>
                <div class="mt-4 flex space-x-3">
                    <Link 
                        :href="route('products.edit', product.id)" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Edit
                    </Link>
                    <button 
                        @click="$emit('delete', product.id)"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    products: {
        type: Array,
        required: true,
    },
});

defineEmits(['delete']);
</script>

