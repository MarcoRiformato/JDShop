<template>
    <Teleport to="body">
        <Transition>
            <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm transition-opacity" @click="$emit('close')"></div>

                <!-- Modal container -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-jd-base-light rounded-2xl shadow-2xl max-w-md w-full" @click.stop>
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-red-500 to-red-600 px-4 sm:px-8 py-4 sm:py-6 rounded-t-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-jd-base-light bg-opacity-20 rounded-full p-1.5 sm:p-2 mr-3 sm:mr-4">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl sm:text-2xl font-bold text-white">Rimuovi Sconto</h2>
                                        <p class="text-red-100 text-xs sm:text-sm mt-1">Conferma l'operazione</p>
                                    </div>
                                </div>
                                <button
                                    @click="$emit('close')"
                                    class="text-white hover:text-red-200 transition-colors p-1.5 sm:p-2 hover:bg-jd-base-light hover:bg-opacity-20 rounded-full"
                                >
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4 sm:p-8">
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 sm:h-16 sm:w-16 rounded-full bg-red-100 mb-4">
                                    <svg class="h-6 w-6 sm:h-8 sm:w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-semibold text-jd-white mb-2">
                                    Rimuovere lo sconto da "{{ product?.title }}"?
                                </h3>
                                <p class="text-sm sm:text-base text-gray-600 mb-6">
                                    Il prezzo verrà ripristinato da <span class="font-semibold text-red-600">€{{ product?.price }}</span> 
                                    a <span class="font-semibold text-green-600">€{{ product?.original_price || product?.price }}</span>
                                </p>
                                
                                <!-- Current discount info -->
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 mb-6">
                                    <div class="text-xs sm:text-sm text-gray-600 mb-2">Sconto attuale:</div>
                                    <div class="text-xl sm:text-2xl font-bold text-red-600">-{{ product?.discount_percentage }}%</div>
                                    <div class="text-xs sm:text-sm text-gray-500 mt-1">
                                        Risparmio: €{{ calculateSavings() }}
                                    </div>
                                </div>

                                <p class="text-xs sm:text-sm text-gray-500 mb-4">
                                    Questa azione non può essere annullata.
                                </p>
                                
                                <!-- Error message -->
                                <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ error }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-4 sm:px-8 py-6 rounded-b-2xl flex items-center justify-end space-x-3 sm:space-x-4">
                            <button
                                @click="$emit('close')"
                                class="px-4 sm:px-6 py-3 text-jd-text bg-jd-base-light border-2 border-jd rounded-xl hover:bg-jd-title transition-colors text-sm sm:text-base font-semibold"
                            >
                                Annulla
                            </button>
                            <button
                                @click="confirmRemoval"
                                :disabled="loading"
                                :class="[
                                    'px-6 sm:px-8 py-3 rounded-xl text-sm sm:text-base font-semibold transition-all transform hover:scale-105',
                                    loading
                                        ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                        : 'bg-gradient-to-r from-red-500 to-red-600 text-white hover:from-red-600 hover:to-red-700 shadow-lg hover:shadow-xl'
                                ]"
                            >
                                <span v-if="loading" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 sm:mr-3 h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Rimuovendo...</span>
                                </span>
                                <span v-else class="flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span class="hidden sm:inline">Rimuovi Sconto</span>
                                    <span class="sm:hidden">Rimuovi</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    product: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close', 'confirm']);

const loading = ref(false);
const error = ref(null);

const calculateSavings = () => {
    if (!props.product) return '0.00';
    const originalPrice = parseFloat(props.product.original_price || props.product.price);
    const currentPrice = parseFloat(props.product.price);
    return (originalPrice - currentPrice).toFixed(2);
};

const confirmRemoval = async () => {
    loading.value = true;
    error.value = null;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        const response = await fetch(`/admin/discounts/remove/${props.product.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || `Errore HTTP: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            emit('confirm', result);
            emit('close');
            window.location.reload();
        } else {
            error.value = result.message || 'Errore sconosciuto durante la rimozione dello sconto';
        }
    } catch (err) {
        error.value = err.message || 'Errore di connessione. Riprova.';
    } finally {
        loading.value = false;
    }
};
</script>
