<template>
    <div class="relative group">
        <img 
            :src="image.thumbnail_url" 
            :alt="'Product image'"
            class="w-full h-48 object-cover rounded-lg shadow-md transition-all duration-200 group-hover:brightness-90"
        >
        
        <!-- Cover badge -->
        <div 
            v-if="image.is_cover" 
            class="absolute top-2 left-2 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold"
        >
            Copertina
        </div>

        <!-- Actions overlay -->
        <div class="absolute inset-0 rounded-lg flex items-end md:items-center justify-center pb-1.5 md:pb-0 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
            <div class="flex flex-col sm:flex-row gap-1.5 pointer-events-auto w-full px-1.5 md:w-auto md:px-0">
                <button 
                    v-if="!image.is_cover"
                    @click="$emit('set-cover', image.id)"
                    class="px-2.5 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 active:bg-green-800 transition-colors text-xs font-medium touch-manipulation shadow-lg md:shadow-none"
                    title="Imposta come copertina"
                >
                    Imposta Copertina
                </button>
                <button 
                    @click="showDeleteModal = true"
                    class="px-2.5 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 active:bg-red-800 transition-colors text-xs font-medium touch-manipulation shadow-lg md:shadow-none"
                    title="Elimina immagine"
                >
                    Elimina
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <Teleport to="body">
        <Transition>
            <div 
                v-if="showDeleteModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @click.self="showDeleteModal = false"
            >
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm transition-opacity"></div>
                
                <!-- Modal -->
                <div class="relative bg-white rounded-lg shadow-xl max-w-sm w-full p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">Conferma Eliminazione</h3>
                    </div>
                    
                    <p class="text-sm text-gray-700 mb-6">
                        Sei sicuro di voler eliminare questa immagine?
                    </p>
                    <p class="text-xs text-red-600 mb-6">
                        Questa azione non può essere annullata.
                    </p>
                    
                    <div class="flex gap-3">
                        <button
                            @click="showDeleteModal = false"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors text-sm font-medium"
                        >
                            Annulla
                        </button>
                        <button
                            @click="confirmDelete"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm font-medium"
                        >
                            Elimina
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    image: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['delete', 'set-cover']);

const showDeleteModal = ref(false);

const confirmDelete = () => {
    emit('delete', props.image.id);
    showDeleteModal.value = false;
};
</script>

