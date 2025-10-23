<template>
    <!-- Modal Overlay -->
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-if="show"
                class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 backdrop-blur-sm"
                @click="closeModal"
            >
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <Transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div 
                            v-if="show"
                            @click.stop
                            class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden"
                        >
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <h2 class="text-xl font-bold text-white">Richiesta Informazioni</h2>
                                </div>
                                <button 
                                    @click="closeModal"
                                    class="p-2 hover:bg-white/20 rounded-lg transition-colors"
                                >
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Success Message -->
                            <div v-if="submitted" class="p-8 text-center">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Messaggio Inviato!</h3>
                                <p class="text-gray-600 mb-6">
                                    Grazie per il tuo interesse. Ti risponderemo il prima possibile.
                                </p>
                                <button
                                    @click="closeModal"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold"
                                >
                                    Chiudi
                                </button>
                            </div>

                            <!-- Contact Form -->
                            <form v-else @submit.prevent="submitForm" class="p-6 space-y-4">
                                <!-- Product Info -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <p class="text-sm font-semibold text-blue-900 mb-1">Prodotto di Interesse:</p>
                                    <p class="text-blue-800">{{ product.title }}</p>
                                    <p class="text-blue-700 font-bold mt-1">€{{ product.price }}</p>
                                </div>

                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nome <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        id="name"
                                        v-model="form.name" 
                                        type="text" 
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="{ 'border-red-500': form.errors.name }"
                                        placeholder="Il tuo nome"
                                    >
                                    <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        id="contact_email"
                                        v-model="form.email" 
                                        type="email" 
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="{ 'border-red-500': form.errors.email }"
                                        placeholder="la.tua.email@esempio.it"
                                    >
                                    <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">
                                        {{ form.errors.email }}
                                    </p>
                                </div>

                                <!-- Phone (optional) -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Telefono (opzionale)
                                    </label>
                                    <input 
                                        id="phone"
                                        v-model="form.phone" 
                                        type="tel" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="{ 'border-red-500': form.errors.phone }"
                                        placeholder="+39 123 456 7890"
                                    >
                                    <p v-if="form.errors.phone" class="text-sm text-red-600 mt-1">
                                        {{ form.errors.phone }}
                                    </p>
                                </div>

                                <!-- Message -->
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                                        Messaggio <span class="text-red-500">*</span>
                                    </label>
                                    <textarea 
                                        id="message"
                                        v-model="form.message" 
                                        rows="5"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="{ 'border-red-500': form.errors.message }"
                                        placeholder="Vorrei ricevere maggiori informazioni su questo prodotto..."
                                    ></textarea>
                                    <p v-if="form.errors.message" class="text-sm text-red-600 mt-1">
                                        {{ form.errors.message }}
                                    </p>
                                </div>

                                <!-- Error message -->
                                <div v-if="form.errors.general" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                                    {{ form.errors.general }}
                                </div>

                                <!-- Action buttons -->
                                <div class="flex gap-3 pt-4 border-t border-gray-200">
                                    <button
                                        type="button"
                                        @click="closeModal"
                                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                                    >
                                        Annulla
                                    </button>
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing"
                                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed font-semibold shadow-lg"
                                    >
                                        <span v-if="form.processing" class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Invio...
                                        </span>
                                        <span v-else>Invia Richiesta</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

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

const emit = defineEmits(['close']);

const submitted = ref(false);

// Generate dynamic pre-filled message based on product being viewed
const generateDefaultMessage = () => {
    const status = props.product.sold_out 
        ? 'Venduto (vorrei sapere se tornerà disponibile)' 
        : 'Disponibile';
    
    return `Salve,

Sono interessato/a al seguente prodotto dal vostro catalogo:

Prodotto: ${props.product.title}
Prezzo: €${props.product.price}
Stato: ${status}

Vorrei ricevere maggiori informazioni e dettagli sull'acquisto di questo articolo.

Cordiali saluti`;
};

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: generateDefaultMessage(),
    product_id: props.product.id,
    product_title: props.product.title,
    product_price: props.product.price,
});

const closeModal = () => {
    if (!form.processing) {
        emit('close');
        // Reset form and submitted state after modal closes
        setTimeout(() => {
            form.reset();
            form.message = generateDefaultMessage(); // Restore default message
            submitted.value = false;
        }, 300);
    }
};

const submitForm = () => {
    form.post(route('contact.send'), {
        preserveScroll: true,
        onSuccess: () => {
            submitted.value = true;
        },
        onError: (errors) => {
            if (errors.general) {
                // General error already set
            }
        },
    });
};

// Handle escape key
const handleEscape = (e) => {
    if (e.key === 'Escape' && props.show && !form.processing) {
        closeModal();
    }
};

// Lock body scroll when modal is open
watch(() => props.show, (show) => {
    if (show) {
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', handleEscape);
    } else {
        document.body.style.overflow = '';
        document.removeEventListener('keydown', handleEscape);
    }
});
</script>

