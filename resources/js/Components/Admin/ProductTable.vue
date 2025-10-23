<template>
    <div>
        <!-- List View (Default) -->
        <div v-if="viewMode === 'list'" class="space-y-3">
            <Link
                v-for="product in products"
                :key="product.id"
                :href="route('products.edit', product.id)"
                class="bg-white rounded-lg shadow hover:shadow-md transition-all cursor-pointer p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 group"
            >
                <!-- Image -->
                <div class="flex-shrink-0 relative">
                    <img
                        v-if="product.cover_image_url"
                        :src="product.cover_image_url"
                        :alt="product.title"
                        class="w-24 h-24 rounded-lg object-cover group-hover:scale-105 transition-transform"
                    >
                    <div v-else class="w-24 h-24 rounded-lg bg-gray-200 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div v-if="product.images_count > 0" class="absolute -bottom-2 -right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                        {{ product.images_count }}
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-1">
                        {{ product.title }}
                    </h3>
                    <p v-if="product.tags" class="text-sm text-gray-500 truncate mb-2">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ product.tags }}
                    </p>
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-bold text-gray-900">€{{ product.price }}</span>
                        <span
                            :class="[
                                'px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full',
                                product.sold_out ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
                            ]"
                        >
                            <span :class="['w-2 h-2 rounded-full mr-1.5', product.sold_out ? 'bg-red-500' : 'bg-green-500']"></span>
                            {{ product.sold_out ? 'Venduto' : 'Disponibile' }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex-shrink-0 flex gap-2 w-full sm:w-auto" @click.stop>
                    <Link
                        :href="route('products.edit', product.id)"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                    >
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifica
                    </Link>
                    <button
                        @click="$emit('delete', product.id)"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium"
                    >
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Elimina
                    </button>
                </div>
            </Link>
        </div>

        <!-- 2x2 Grid View -->
        <div v-else-if="viewMode === 'grid-2'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <Link
                v-for="product in products"
                :key="product.id"
                :href="route('products.edit', product.id)"
                class="bg-white rounded-lg shadow hover:shadow-xl transition-all cursor-pointer overflow-hidden group"
            >
                <!-- Image -->
                <div class="relative">
                    <img
                        v-if="product.cover_image_url"
                        :src="product.cover_image_url"
                        :alt="product.title"
                        class="w-full aspect-square object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                    <div v-else class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div v-if="product.images_count > 0" class="absolute top-4 right-4 bg-blue-600 text-white text-sm font-bold px-3 py-1.5 rounded-full shadow-lg">
                        {{ product.images_count }} {{ product.images_count === 1 ? 'immagine' : 'immagini' }}
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                        {{ product.title }}
                    </h3>
                    <p v-if="product.tags" class="text-sm text-gray-500 mb-3 line-clamp-1">
                        {{ product.tags }}
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-3xl font-bold text-gray-900">€{{ product.price }}</span>
                        <span
                            :class="[
                                'px-3 py-1.5 inline-flex items-center text-xs font-semibold rounded-full',
                                product.sold_out ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
                            ]"
                        >
                            <span :class="['w-2 h-2 rounded-full mr-2', product.sold_out ? 'bg-red-500' : 'bg-green-500']"></span>
                            {{ product.sold_out ? 'Venduto' : 'Disponibile' }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3" @click.stop>
                        <Link
                            :href="route('products.edit', product.id)"
                            class="flex-1 flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifica
                        </Link>
                        <button
                            @click="$emit('delete', product.id)"
                            class="flex-1 flex items-center justify-center px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Elimina
                        </button>
                    </div>
                </div>
            </Link>
        </div>

        <!-- 4x4 Grid View -->
        <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <Link
                v-for="product in products"
                :key="product.id"
                :href="route('products.edit', product.id)"
                class="bg-white rounded-lg shadow hover:shadow-lg transition-all cursor-pointer overflow-hidden group"
            >
                <!-- Image -->
                <div class="relative">
                    <img
                        v-if="product.cover_image_url"
                        :src="product.cover_image_url"
                        :alt="product.title"
                        class="w-full aspect-square object-cover group-hover:scale-110 transition-transform duration-300"
                    >
                    <div v-else class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div v-if="product.images_count > 0" class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                        {{ product.images_count }}
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors line-clamp-2 text-sm">
                        {{ product.title }}
                    </h3>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-lg font-bold text-gray-900">€{{ product.price }}</span>
                        <span
                            :class="[
                                'w-3 h-3 rounded-full',
                                product.sold_out ? 'bg-red-500' : 'bg-green-500'
                            ]"
                            :title="product.sold_out ? 'Venduto' : 'Disponibile'"
                        ></span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2" @click.stop>
                        <Link
                            :href="route('products.edit', product.id)"
                            class="flex-1 flex items-center justify-center px-2 py-1.5 bg-blue-600 text-white rounded text-xs font-medium hover:bg-blue-700 transition-colors"
                            title="Modifica"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </Link>
                        <button
                            @click="$emit('delete', product.id)"
                            class="flex-1 flex items-center justify-center px-2 py-1.5 bg-red-600 text-white rounded text-xs font-medium hover:bg-red-700 transition-colors"
                            title="Elimina"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </Link>
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
    viewMode: {
        type: String,
        default: 'list',
        validator: (value) => ['list', 'grid-2', 'grid-4'].includes(value),
    },
});

defineEmits(['delete', 'preview']);
</script>
