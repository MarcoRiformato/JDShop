<template>
    <Teleport to="body">
        <Transition>
            <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black bg-opacity-20" @click="$emit('close')"></div>

                <!-- Modal container -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-white rounded-lg sm:rounded-2xl shadow-2xl max-w-md w-full" @click.stop>
                        <!-- Header -->
                        <div class="px-6 py-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-red-100 rounded-full">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-jd-title">Rimuovi Sconto</h3>
                                </div>
                                <button
                                    @click="$emit('close')"
                                    class="text-gray-400 hover:text-gray-600 transition-colors p-1"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Content -->
                            <div class="mb-6">
                                <p class="text-gray-700 mb-4">
                                    Sei sicuro di voler rimuovere lo sconto da <strong>"{{ product?.title }}"</strong>?
                                </p>
                                
                                <p class="text-sm text-gray-600 mb-4">
                                    Il prezzo verrà ripristinato da <span class="font-semibold text-red-600">€{{ product?.price }}</span> 
                                    a <span class="font-semibold text-green-600">€{{ product?.original_price || product?.price }}</span>
                                </p>
                                
                                <!-- Current discount info -->
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                    <div class="text-sm text-gray-600 mb-1">Sconto attuale:</div>
                                    <div class="text-2xl font-bold text-red-600">-{{ product?.discount_percentage }}%</div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        Risparmio: €{{ calculateSavings() }}
                                    </div>
                                </div>

                                <p class="text-sm text-red-600">
                                    Questa azione non può essere annullata.
                                </p>
                                
                                <!-- Error message -->
                                <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mt-4">
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
                        <div class="flex gap-3 px-6 pb-6">
                            <button
                                @click="$emit('close')"
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                            >
                                Annulla
                            </button>
                            <button
                                @click="confirmRemoval"
                                :disabled="loading"
                                :class="[
                                    'flex-1 px-4 py-2 rounded-lg font-medium transition-colors',
                                    loading
                                        ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                        : 'bg-red-600 text-white hover:bg-red-700'
                                ]"
                            >
                                <span v-if="loading" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Rimuovendo...
                                </span>
                                <span v-else>
                                    Rimuovi Sconto
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
