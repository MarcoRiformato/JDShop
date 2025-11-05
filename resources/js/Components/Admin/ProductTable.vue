<template>
    <div>
        <!-- List View (Default) -->
        <div v-if="viewMode === 'list'" class="space-y-3">
            <div
                v-for="product in products"
                :key="product.id"
                @click="handleProductClick(product, $event)"
                :class="[
                    'bg-white rounded-lg shadow hover:shadow-md transition-all p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 group',
                    discountModeActive && isSelected(product.id) ? 'ring-4 ring-blue-500 ring-opacity-50' : '',
                    discountModeActive && hasAnyDiscount(product) ? 'opacity-50 bg-gray-50 cursor-not-allowed pointer-events-none' : '',
                    !discountModeActive ? 'cursor-pointer touch-manipulation' : ''
                ]"
            >
                <!-- Checkbox -->
                <div v-if="discountModeActive" class="flex-shrink-0">
                    <input
                        :id="`product-${product.id}`"
                        :checked="isSelected(product.id)"
                        :disabled="hasAnyDiscount(product)"
                        @change="!hasAnyDiscount(product) && toggleSelection(product.id)"
                        type="checkbox"
                        :class="[
                            'w-4 h-4 text-blue-600 bg-gray-50 border-gray-300 rounded focus:ring-blue-500 focus:ring-2',
                            hasAnyDiscount(product) ? 'opacity-50 cursor-not-allowed' : ''
                        ]"
                    >
                </div>

                <!-- Image -->
                <div class="flex-shrink-0 relative">
                    <!-- Desktop: Show 3 images if available -->
                    <div v-if="product.images && product.images.length >= 3" class="hidden sm:flex gap-1">
                        <div class="relative">
                            <img
                                :src="product.images[0].thumbnail_url || product.images[0].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover group-hover:scale-105 transition-transform"
                            >
                            <div v-if="product.images[0].is_cover" class="absolute top-0.5 left-0.5 bg-blue-600 text-white text-[9px] font-bold px-1 py-0.5 rounded">
                                Copertina
                            </div>
                        </div>
                        <div class="relative">
                            <img
                                :src="product.images[1].thumbnail_url || product.images[1].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover group-hover:scale-105 transition-transform"
                            >
                        </div>
                        <div class="relative">
                            <img
                                :src="product.images[2].thumbnail_url || product.images[2].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover group-hover:scale-105 transition-transform"
                            >
                            <div v-if="product.images_count > 3" class="absolute -bottom-2 -right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                +{{ product.images_count - 3 }}
                            </div>
                        </div>
                    </div>
                    <!-- Desktop: Show 2 images if 2 available -->
                    <div v-else-if="product.images && product.images.length === 2" class="hidden sm:flex gap-1">
                        <div class="relative">
                            <img
                                :src="product.images[0].thumbnail_url || product.images[0].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover group-hover:scale-105 transition-transform"
                            >
                            <div v-if="product.images[0].is_cover" class="absolute top-0.5 left-0.5 bg-blue-600 text-white text-[9px] font-bold px-1 py-0.5 rounded">
                                Copertina
                            </div>
                        </div>
                        <div class="relative">
                            <img
                                :src="product.images[1].thumbnail_url || product.images[1].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover group-hover:scale-105 transition-transform"
                            >
                        </div>
                    </div>
                    <!-- Desktop: Single image if only one exists -->
                    <div v-else class="hidden sm:block">
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
                    </div>
                    <!-- Mobile: Show 3 images if available -->
                    <div v-if="product.images && product.images.length >= 3" class="sm:hidden flex gap-1">
                        <div class="relative">
                            <img
                                :src="product.images[0].thumbnail_url || product.images[0].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover"
                            >
                            <div v-if="product.images[0].is_cover" class="absolute top-0.5 left-0.5 bg-blue-600 text-white text-[9px] font-bold px-1 py-0.5 rounded">
                                Copertina
                            </div>
                        </div>
                        <div class="relative">
                            <img
                                :src="product.images[1].thumbnail_url || product.images[1].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover"
                            >
                        </div>
                        <div class="relative">
                            <img
                                :src="product.images[2].thumbnail_url || product.images[2].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover"
                            >
                            <div v-if="product.images_count > 3" class="absolute -bottom-2 -right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                +{{ product.images_count - 3 }}
                            </div>
                        </div>
                    </div>
                    <!-- Mobile: Show 2 images if 2 available -->
                    <div v-else-if="product.images && product.images.length === 2" class="sm:hidden flex gap-1.5">
                        <div class="relative">
                            <img
                                :src="product.images[0].thumbnail_url || product.images[0].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover"
                            >
                            <div v-if="product.images[0].is_cover" class="absolute top-1 left-1 bg-blue-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                                Copertina
                            </div>
                        </div>
                        <div class="relative">
                            <img
                                :src="product.images[1].thumbnail_url || product.images[1].url"
                                :alt="product.title"
                                class="w-20 h-20 rounded-lg object-cover"
                            >
                        </div>
                    </div>
                    <!-- Mobile: Single image if only one exists -->
                    <div v-else class="sm:hidden">
                        <img
                            v-if="product.cover_image_url"
                            :src="product.cover_image_url"
                            :alt="product.title"
                            class="w-24 h-24 rounded-lg object-cover"
                        >
                        <div v-else class="w-24 h-24 rounded-lg bg-gray-200 flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <!-- Discount Badge - Active -->
                    <div v-if="product.has_active_discount" class="absolute top-2 right-2 z-10">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold px-2 py-1 rounded-lg shadow-lg border border-orange-400">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                -{{ product.discount_percentage }}%
                            </div>
                        </div>
                    </div>
                    <!-- Future Discount Badge -->
                    <div v-if="product.has_future_discount && !product.has_active_discount" class="absolute top-2 right-2 z-10">
                        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 text-white text-xs font-bold px-2 py-1 rounded-lg shadow-lg border border-yellow-400">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Dal {{ formatDate(product.discount_start_date) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0 overflow-hidden">
                    <h3 class="text-lg font-semibold text-gray-700 group-hover:text-jd-accent transition-colors mb-1 line-clamp-2 overflow-hidden break-words">
                        {{ product.title }}
                    </h3>
                    <p v-if="product.tags" class="text-sm text-gray-500 truncate mb-2 overflow-hidden">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ product.tags }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <span v-if="product.has_active_discount" class="text-lg text-gray-500 line-through">€{{ product.original_price }}</span>
                            <span v-if="product.has_future_discount && !product.has_active_discount" class="text-lg text-gray-500 line-through">€{{ product.original_price || product.price }}</span>
                            <span class="text-2xl font-bold" :class="product.has_future_discount && !product.has_active_discount ? 'text-yellow-400' : 'text-gray-900'">€{{ product.has_future_discount && !product.has_active_discount ? getFutureDiscountedPrice(product) : product.price }}</span>
                        </div>
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
                <div class="flex-shrink-0 w-full sm:w-auto flex gap-2 flex-wrap" @click.stop>
                    <Link
                        :href="route('products.edit', product.id)"
                        class="btn-jd-primary flex-1 sm:flex-initial inline-flex items-center justify-center px-3 py-2 sm:px-4 rounded-lg text-sm font-medium"
                    >
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="hidden sm:inline">Modifica</span>
                    </Link>
                    <button
                        v-if="hasAnyDiscount(product)"
                        @click="$emit('remove-discount', product)"
                        class="flex-1 sm:flex-initial inline-flex items-center justify-center px-3 py-2 sm:px-4 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium"
                        title="Rimuovi sconto"
                    >
                        Rimuovi sconto
                    </button>
                </div>
            </div>
        </div>

        <!-- 2x2 Grid View -->
        <div v-else-if="viewMode === 'grid-2'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                v-for="product in products"
                :key="product.id"
                @click="handleProductClick(product, $event)"
                :class="[
                    'bg-white rounded-lg shadow hover:shadow-xl transition-all overflow-hidden group relative cursor-pointer',
                    discountModeActive && isSelected(product.id) ? 'ring-4 ring-blue-500 ring-opacity-50' : '',
                    discountModeActive && product.has_active_discount ? 'opacity-50 bg-gray-50 cursor-not-allowed pointer-events-none' : ''
                ]"
            >
                <!-- Checkbox -->
                <div v-if="discountModeActive" class="absolute top-4 left-4 z-10">
                    <input
                        :id="`product-${product.id}`"
                        :checked="isSelected(product.id)"
                        :disabled="hasAnyDiscount(product)"
                        @change="!hasAnyDiscount(product) && toggleSelection(product.id)"
                        type="checkbox"
                        :class="[
                            'w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 shadow-sm',
                            hasAnyDiscount(product) ? 'opacity-50 cursor-not-allowed' : ''
                        ]"
                    >
                </div>

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
                    <div v-if="product.images_count > 0 && !hasAnyDiscount(product)" class="absolute top-4 right-4 bg-jd-accent text-white text-sm font-bold px-3 py-1.5 rounded-full shadow-lg">
                        {{ product.images_count }} {{ product.images_count === 1 ? 'immagine' : 'immagini' }}
                    </div>
                    <!-- Discount Badge - Active -->
                    <div v-if="product.has_active_discount" class="absolute top-4 right-4 z-10">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-bold px-3 py-1.5 rounded-lg shadow-lg border border-orange-400">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-center leading-none">-{{ product.discount_percentage }}%</span>
                            </div>
                        </div>
                    </div>
                    <!-- Future Discount Badge -->
                    <div v-if="product.has_future_discount && !product.has_active_discount" class="absolute top-4 right-4 z-10">
                        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 text-white text-sm font-bold px-3 py-1.5 rounded-lg shadow-lg border border-yellow-400">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-center leading-none">Dal {{ formatDate(product.discount_start_date) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 min-w-0">
                    <h3 class="text-base sm:text-xl font-bold text-gray-700 mb-2 group-hover:text-jd-accent transition-colors line-clamp-2 overflow-hidden break-words">
                        {{ product.title }}
                    </h3>
                    <p v-if="product.tags" class="text-sm text-gray-500 mb-3 line-clamp-1 overflow-hidden">
                        {{ product.tags }}
                    </p>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span v-if="product.has_active_discount" class="text-lg text-gray-500 line-through">€{{ product.original_price }}</span>
                            <span v-if="product.has_future_discount && !product.has_active_discount" class="text-lg text-gray-500 line-through">€{{ product.original_price || product.price }}</span>
                            <span class="text-3xl font-bold" :class="product.has_future_discount && !product.has_active_discount ? 'text-yellow-400' : 'text-gray-900'">€{{ product.has_future_discount && !product.has_active_discount ? getFutureDiscountedPrice(product) : product.price }}</span>
                        </div>
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
                    <div class="flex flex-wrap gap-3" @click.stop>
                        <Link
                            :href="route('products.edit', product.id)"
                            class="btn-jd-primary flex-1 min-w-[120px] flex items-center justify-center px-4 py-2.5 rounded-lg font-medium"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifica
                        </Link>
                        <button
                            v-if="product.has_active_discount"
                            @click="$emit('remove-discount', product)"
                            class="flex-1 min-w-[120px] flex items-center justify-center px-4 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium"
                            title="Rimuovi sconto"
                        >
                            Rimuovi sconto
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4x4 Grid View -->
        <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div
                v-for="product in products"
                :key="product.id"
                @click="handleProductClick(product, $event)"
                :class="[
                    'bg-white rounded-lg shadow hover:shadow-lg transition-all overflow-hidden group relative cursor-pointer w-full max-w-full',
                    discountModeActive && isSelected(product.id) ? 'ring-4 ring-blue-500 ring-opacity-50' : '',
                    discountModeActive && product.has_active_discount ? 'opacity-50 bg-gray-50 cursor-not-allowed pointer-events-none' : ''
                ]"
                style="width: 100%; max-width: 100%; overflow: hidden;"
            >
                <!-- Checkbox -->
                <div v-if="discountModeActive" class="absolute top-2 left-2 z-10">
                    <input
                        :id="`product-${product.id}`"
                        :checked="isSelected(product.id)"
                        :disabled="hasAnyDiscount(product)"
                        @change="!hasAnyDiscount(product) && toggleSelection(product.id)"
                        type="checkbox"
                        :class="[
                            'w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 shadow-sm',
                            hasAnyDiscount(product) ? 'opacity-50 cursor-not-allowed' : ''
                        ]"
                    >
                </div>

                <!-- Images -->
                <div class="relative">
                    <!-- Show grid of images if multiple images exist -->
                    <div v-if="product.images && product.images.length > 1" class="grid grid-cols-2 gap-0.5">
                        <img
                            v-for="image in product.images"
                            :key="image.id"
                            :src="image.thumbnail_url"
                            :alt="product.title"
                            class="w-full aspect-square object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                    </div>
                    <!-- Show single image or placeholder -->
                    <template v-else>
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
                    </template>
                    
                    <!-- Image count badge (only if more than 2 images and no discount badge) -->
                    <div v-if="product.images_count > 2 && !hasAnyDiscount(product)" class="absolute top-2 right-2 bg-jd-accent text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                        {{ product.images_count }}
                    </div>
                    
                    <!-- Discount Badge - Active -->
                    <div v-if="product.has_active_discount" class="absolute top-2 right-2 z-10">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold px-2 py-1 rounded-lg shadow-lg border border-orange-400">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                -{{ product.discount_percentage }}%
                            </div>
                        </div>
                    </div>
                    <!-- Future Discount Badge -->
                    <div v-if="product.has_future_discount && !product.has_active_discount" class="absolute top-2 right-2 z-10">
                        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 text-white text-xs font-bold px-2 py-1 rounded-lg shadow-lg border border-yellow-400">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Dal {{ formatDate(product.discount_start_date) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4 min-w-0 overflow-hidden">
                    <h3 class="font-semibold text-gray-700 mb-1 group-hover:text-jd-accent transition-colors line-clamp-2 text-sm overflow-hidden break-words" style="overflow: hidden; text-overflow: ellipsis; word-break: break-all;">
                        {{ product.title }}
                    </h3>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-1">
                            <span v-if="product.has_active_discount" class="text-sm text-gray-500 line-through">€{{ product.original_price }}</span>
                            <span v-if="product.has_future_discount && !product.has_active_discount" class="text-sm text-gray-500 line-through">€{{ product.original_price || product.price }}</span>
                            <span class="text-lg font-bold" :class="product.has_future_discount && !product.has_active_discount ? 'text-yellow-400' : 'text-gray-900'">€{{ product.has_future_discount && !product.has_active_discount ? getFutureDiscountedPrice(product) : product.price }}</span>
                        </div>
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
                            class="btn-jd-primary flex-1 flex items-center justify-center px-2 py-1.5 rounded text-xs font-medium"
                            title="Modifica"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </Link>
                        <button
                            v-if="product.has_active_discount"
                            @click="$emit('remove-discount', product)"
                            class="flex-1 flex items-center justify-center px-2 py-1.5 bg-red-500 text-white rounded text-xs font-medium hover:bg-red-600 transition-colors"
                            title="Rimuovi sconto"
                        >
                            Rimuovi sconto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    products: {
        type: Array,
        required: true,
    },
    viewMode: {
        type: String,
        default: 'list',
        validator: (value) => ['list', 'grid-2', 'grid-4'].includes(value),
    },
    selectedProducts: {
        type: Array,
        default: () => [],
    },
    discountModeActive: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['selection-change', 'remove-discount']);

// Format date helper - dd/mm/yy format
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear()).slice(-2);
    return `${day}/${month}/${year}`;
};

// Check if product has any discount (active or future)
const hasAnyDiscount = (product) => {
    return product.has_active_discount || product.has_future_discount;
};

// Calculate future discounted price
const getFutureDiscountedPrice = (product) => {
    if (!product.has_future_discount || !product.discount_percentage) {
        return null;
    }
    
    // Use original_price if available, otherwise use current price
    const basePrice = product.original_price || product.price;
    return Math.floor(basePrice * (1 - (product.discount_percentage / 100)));
};

const isSelected = (productId) => {
    return props.selectedProducts.some(p => p.id === productId);
};

const toggleSelection = (productId) => {
    const product = props.products.find(p => p.id === productId);
    if (!product) return;

    let newSelection;
    if (isSelected(productId)) {
        // Remove from selection
        newSelection = props.selectedProducts.filter(p => p.id !== productId);
    } else {
        // Add to selection
        newSelection = [...props.selectedProducts, product];
    }

    emit('selection-change', newSelection);
};

const handleProductClick = (product, event) => {
    // Check if click was on a button, link, or checkbox
    const target = event.target;
    const isInteractive = target.closest('button') || 
                          target.closest('a') || 
                          target.closest('input[type="checkbox"]') ||
                          target.tagName === 'BUTTON' ||
                          target.tagName === 'A' ||
                          target.tagName === 'INPUT';
    
    // If clicking on interactive element, let it handle the event
    if (isInteractive) {
        return;
    }
    
    // In discount mode, handle selection
    if (props.discountModeActive && !hasAnyDiscount(product)) {
        event.preventDefault();
        event.stopPropagation();
        toggleSelection(product.id);
        return;
    }
    
    // If not in discount mode, open edit page
    if (!props.discountModeActive) {
        event.preventDefault();
        event.stopPropagation();
        router.visit(route('products.edit', product.id));
    }
};

</script>

<style scoped>
/* Force card width constraints */
.bg-white.rounded-lg.shadow {
    max-width: 100% !important;
    overflow: hidden !important;
}

/* Force title to stay within bounds */
h3 {
    overflow: hidden !important;
    word-break: break-all !important;
    word-wrap: break-word !important;
    max-width: 100% !important;
    display: block !important;
    text-overflow: ellipsis !important;
}

/* Grid item constraints */
.grid > div {
    max-width: 100% !important;
    overflow: hidden !important;
}
</style>