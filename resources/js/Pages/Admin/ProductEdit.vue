<template>
    <AdminLayout>
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <Link :href="route('admin.dashboard')" class="text-blue-600 hover:text-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Dashboard
                </Link>
                <h1 class="text-3xl font-bold text-gray-900 mt-4">Edit Equipment</h1>
                <p class="text-gray-600 mt-1">Update item details and manage photos</p>
            </div>

            <!-- Success message -->
            <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ $page.props.flash.success }}
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Product form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Equipment Details</h2>
                    <form @submit.prevent="submit">
                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    id="title"
                                    v-model="form.title" 
                                    type="text" 
                                    maxlength="35"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.title }"
                                    required
                                >
                                <div class="flex justify-between mt-1">
                                    <p v-if="form.errors.title" class="text-sm text-red-600">{{ form.errors.title }}</p>
                                    <p class="text-sm text-gray-500 ml-auto">{{ form.title.length }}/35</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea 
                                    id="description"
                                    v-model="form.description" 
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.description }"
                                ></textarea>
                                <p v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</p>
                            </div>

                            <!-- Tags -->
                            <div>
                                <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tags
                                </label>
                                <input 
                                    id="tags"
                                    v-model="form.tags" 
                                    type="text" 
                                    placeholder="e.g., clothing,t-shirt,casual"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': form.errors.tags }"
                                >
                                <p class="text-sm text-gray-500 mt-1">Separate tags with commas</p>
                                <p v-if="form.errors.tags" class="text-sm text-red-600 mt-1">{{ form.errors.tags }}</p>
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input 
                                        id="price"
                                        v-model="form.price" 
                                        type="number" 
                                        step="0.01"
                                        min="0"
                                        class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="{ 'border-red-500': form.errors.price }"
                                        required
                                    >
                                </div>
                                <p v-if="form.errors.price" class="text-sm text-red-600 mt-1">{{ form.errors.price }}</p>
                            </div>

                            <!-- Sold Out -->
                            <div class="flex items-center">
                                <input 
                                    id="sold_out"
                                    v-model="form.sold_out" 
                                    type="checkbox"
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="sold_out" class="ml-3 text-sm font-medium text-gray-700">
                                    Mark as sold out
                                </label>
                            </div>

                            <!-- Submit button -->
                            <div class="pt-4 border-t border-gray-200">
                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ form.processing ? 'Updating...' : 'Update Product' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Image management -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Equipment Photos</h2>
                    
                    <!-- Image uploader -->
                    <ImageUploader 
                        :product-id="product.id"
                        @uploaded="handleImageUploaded"
                    />

                    <!-- Existing images -->
                    <div v-if="images.length > 0" class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">
                            Current Images ({{ images.length }})
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <ImagePreview
                                v-for="image in images"
                                :key="image.id"
                                :image="image"
                                @delete="handleImageDelete"
                                @set-cover="handleSetCover"
                            />
                        </div>
                    </div>

                    <div v-else class="mt-6 text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No images yet</p>
                        <p class="text-xs text-gray-400">Upload some images to get started</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ImageUploader from '@/Components/Admin/ImageUploader.vue';
import ImagePreview from '@/Components/Admin/ImagePreview.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const images = ref([...props.product.images]);

const form = useForm({
    title: props.product.title,
    description: props.product.description,
    tags: props.product.tags,
    price: props.product.price,
    sold_out: props.product.sold_out,
});

const submit = () => {
    form.put(route('products.update', props.product.id));
};

const handleImageUploaded = (image) => {
    images.value.push(image);
};

const handleImageDelete = async (imageId) => {
    if (!confirm('Are you sure you want to delete this image?')) return;

    try {
        await axios.delete(route('images.delete', imageId));
        images.value = images.value.filter(img => img.id !== imageId);
    } catch (error) {
        alert('Failed to delete image: ' + (error.response?.data?.message || error.message));
    }
};

const handleSetCover = async (imageId) => {
    try {
        await axios.patch(route('images.set-cover', imageId));
        images.value = images.value.map(img => ({
            ...img,
            is_cover: img.id === imageId,
        }));
    } catch (error) {
        alert('Failed to set cover image: ' + (error.response?.data?.message || error.message));
    }
};
</script>

