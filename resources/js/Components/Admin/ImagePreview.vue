<template>
    <div class="relative group">
        <img 
            :src="image.thumbnail_url" 
            :alt="'Product image'"
            class="w-full h-48 object-cover rounded-lg shadow-md"
        >
        
        <!-- Cover badge -->
        <div 
            v-if="image.is_cover" 
            class="absolute top-2 left-2 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold"
        >
            Copertina
        </div>

        <!-- Actions overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
            <div class="flex space-x-2">
                <button 
                    v-if="!image.is_cover"
                    @click="$emit('set-cover', image.id)"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium"
                    title="Imposta come copertina"
                >
                    Imposta Copertina
                </button>
                <button 
                    @click="$emit('delete', image.id)"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium"
                    title="Elimina immagine"
                >
                    Elimina
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    image: {
        type: Object,
        required: true,
    },
});

defineEmits(['delete', 'set-cover']);
</script>

