<template>
    <div>
        <div v-if="inquiries.length === 0" class="text-center py-12 text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p>Nessuna richiesta ancora</p>
        </div>

        <div v-else class="space-y-6">
            <div 
                v-for="inquiry in inquiries" 
                :key="inquiry.id"
                class="relative pl-8 pb-8 border-l-2 border-gray-300 last:border-l-0 last:pb-0"
            >
                <!-- Timeline dot -->
                <div class="absolute left-0 top-0 -translate-x-1/2">
                    <div class="h-4 w-4 rounded-full bg-jd-accent border-2 border-white shadow-sm"></div>
                </div>

                <!-- Content -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-start gap-3 flex-1">
                            <!-- Product Image -->
                            <div v-if="inquiry.product_cover_image" class="flex-shrink-0">
                                <img 
                                    :src="inquiry.product_cover_image" 
                                    :alt="inquiry.product_title"
                                    class="w-16 h-16 object-cover rounded-lg border border-gray-300"
                                >
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ inquiry.created_at }}</p>
                                <p v-if="inquiry.product_title" class="text-sm text-jd-accent font-medium mt-1">
                                    Prodotto: {{ inquiry.product_title }}
                                </p>
                            </div>
                        </div>
                        <span 
                            v-if="inquiry.is_responded"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                        >
                            Risposta inviata
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ inquiry.message }}</p>
                    </div>

                    <!-- Contact Info -->
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 mb-3">
                        <div v-if="inquiry.email_used !== currentCustomerEmail">
                            <span class="font-medium">Email usata:</span> {{ inquiry.email_used }}
                        </div>
                        <div v-if="inquiry.phone">
                            <span class="font-medium">Telefono:</span> {{ inquiry.phone }}
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    <div v-if="inquiry.admin_notes" class="mt-3 pt-3 border-t border-gray-300">
                        <p class="text-xs font-medium text-gray-700 mb-1">Note Amministratore:</p>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap italic">{{ inquiry.admin_notes }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4">
                        <button
                            @click="editNotes(inquiry)"
                            class="px-3 py-1.5 text-xs bg-jd-accent text-white rounded hover:bg-blue-700 transition-colors"
                        >
                            {{ inquiry.admin_notes ? 'Modifica Note' : 'Aggiungi Note' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Edit Modal -->
        <div 
            v-if="editingInquiry"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
            @click.self="cancelEdit"
        >
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aggiungi/Modifica Note</h3>
                <textarea
                    v-model="notesForm.admin_notes"
                    rows="4"
                    class="input-jd w-full"
                    placeholder="Aggiungi note interne per questa richiesta..."
                ></textarea>
                <div class="mt-4 flex justify-end gap-2">
                    <button
                        @click="cancelEdit"
                        class="px-4 py-2 text-gray-700 bg-gray-50 rounded-lg hover:bg-whiteer transition-colors"
                    >
                        Annulla
                    </button>
                    <button
                        @click="saveNotes"
                        class="px-4 py-2 bg-jd-accent text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Salva
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    inquiries: {
        type: Array,
        required: true,
    },
    currentCustomerEmail: {
        type: String,
        default: '',
    },
});

const editingInquiry = ref(null);
const notesForm = useForm({
    admin_notes: '',
});

const editNotes = (inquiry) => {
    editingInquiry.value = inquiry;
    notesForm.admin_notes = inquiry.admin_notes || '';
};

const cancelEdit = () => {
    editingInquiry.value = null;
    notesForm.reset();
};

const saveNotes = () => {
    if (!editingInquiry.value) return;

    notesForm.post(route('inquiries.update', editingInquiry.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            cancelEdit();
            // Refresh page to get updated data
            window.location.reload();
        },
    });
};

</script>



