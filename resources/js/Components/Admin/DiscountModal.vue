<template>
    <Teleport to="body">
        <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop - clickable to close -->
            <div class="fixed inset-0 bg-black bg-opacity-20" @click="$emit('close')"></div>
            
            <!-- Modal container -->
            <div class="flex min-h-full items-center justify-center p-1 sm:p-4 pointer-events-none">
                <div class="relative bg-jd-base-light rounded-lg sm:rounded-2xl shadow-2xl w-full max-w-[calc(100vw-0.5rem)] sm:max-w-2xl max-h-[95vh] sm:max-h-[90vh] overflow-hidden pointer-events-auto" @click.stop>
                    <!-- Header with icon -->
                    <div class="bg-gradient-jd-accent px-3 sm:px-8 py-3 sm:py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 rounded-full p-1 sm:p-2 mr-2 sm:mr-4">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-2xl font-bold text-jd-white">Gestisci Sconti</h2>
                                    <p class="text-jd-text text-xs sm:text-sm mt-0.5">Applica sconti ai prodotti selezionati</p>
                                </div>
                            </div>
                            <button 
                                @click="$emit('close')"
                                class="text-white hover:text-orange-200 transition-colors p-1.5 sm:p-2 hover:bg-white hover:bg-opacity-20 rounded-full"
                            >
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Scrollable Content -->
                    <div class="overflow-y-auto max-h-[calc(95vh-200px)] sm:max-h-[calc(90vh-180px)]">
                        <div class="p-2 sm:p-8 space-y-2 sm:space-y-6">
                            <!-- Selected Products Info -->
                            <div class="bg-jd-title border border-jd-accent rounded-xl p-3 sm:p-6">
                                <div class="flex items-center">
                                    <div class="bg-jd-accent bg-opacity-20 rounded-full p-2 sm:p-3 mr-2 sm:mr-4 flex-shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-jd-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-sm sm:text-lg font-semibold text-jd-white">
                                            {{ selectedProducts.length }} {{ selectedProducts.length === 1 ? 'prodotto selezionato' : 'prodotti selezionati' }}
                                        </h3>
                                        <p class="text-jd-text text-xs sm:text-sm mt-0.5">I prezzi verranno aggiornati automaticamente</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Discount Percentage -->
                            <div>
                                <label class="block text-sm sm:text-lg font-semibold text-jd-white mb-2 sm:mb-4">Percentuale di Sconto</label>
                                
                                <!-- Predefined buttons and custom input on same row -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-2">
                                    <button
                                        v-for="percentage in predefinedPercentages"
                                        :key="percentage"
                                        @click="selectPercentage(percentage)"
                                        :class="[
                                            'px-2 sm:px-4 py-2 sm:py-3 rounded-lg border-2 font-semibold transition-all transform hover:scale-105 text-center',
                                            selectedPercentage === percentage
                                                ? 'border-jd-accent bg-jd-accent bg-opacity-20 text-jd-accent shadow-lg'
                                                : 'border-jd hover:border-jd-accent text-jd-text hover:bg-jd-base-lighter'
                                        ]"
                                    >
                                        <div class="text-sm sm:text-xl font-bold">-{{ percentage }}%</div>
                                    </button>
                                    
                                    <!-- Custom percentage input -->
                                    <div class="relative">
                                        <input
                                            v-model.number="customPercentage"
                                            @input="selectCustomPercentage"
                                            type="number"
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            placeholder="Altro"
                                            :class="[
                                                'w-full h-full px-3 sm:px-4 py-2.5 sm:py-3 text-center border-2 rounded-lg focus:ring-2 focus:ring-jd-accent focus:border-jd-accent transition-all font-semibold text-sm sm:text-base error-field bg-white',
                                                errors.percentage ? 'border-red-500 bg-red-50' : 'border-jd'
                                            ]"
                                        >
                                    </div>
                                </div>
                                <p v-if="errors.percentage" class="text-xs sm:text-sm text-red-600 font-medium flex items-center mb-2 sm:mb-4">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ errors.percentage }}
                                </p>
                            </div>

                            <!-- Date Range -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-6">
                                <!-- Start Date -->
                                <div>
                                    <label class="block text-sm sm:text-lg font-semibold text-jd-white mb-2">Data Inizio</label>
                                    <input
                                        v-model="startDate"
                                        type="date"
                                        :min="minDate"
                                        :class="[
                                            'w-full px-3 sm:px-4 py-2 sm:py-3 text-base sm:text-lg border-2 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-orange-200 focus:border-orange-500 transition-all error-field',
                                            errors.startDate ? 'border-red-500 bg-red-50' : 'border-jd'
                                        ]"
                                        @input="validateStartDate"
                                        @click="$event.target.showPicker()"
                                    >
                                    <p v-if="!errors.startDate" class="mt-1 text-xs sm:text-sm text-jd-muted">Se non specificata, lo sconto inizia subito</p>
                                    <p v-if="errors.startDate" class="mt-1 text-xs sm:text-sm text-red-600 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ errors.startDate }}
                                    </p>
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label class="block text-sm sm:text-lg font-semibold text-jd-white mb-2">Data Fine</label>
                                    <div class="space-y-2">
                                        <input
                                            v-model="endDate"
                                            :disabled="neverExpires"
                                            type="date"
                                            :min="startDate || minDate"
                                            :class="[
                                                'w-full px-3 sm:px-4 py-2 sm:py-3 text-base sm:text-lg border-2 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-orange-200 focus:border-orange-500 transition-all error-field',
                                                neverExpires ? 'border-jd bg-jd-title text-jd-muted cursor-not-allowed' : 
                                                errors.endDate ? 'border-red-500 bg-red-50' : 'border-jd cursor-pointer'
                                            ]"
                                            @input="validateEndDate"
                                            @click="!neverExpires && $event.target.showPicker()"
                                        >
                                        <label class="flex items-center">
                                            <input
                                                v-model="neverExpires"
                                                type="checkbox"
                                                class="w-4 h-4 sm:w-5 sm:h-5 rounded border-jd text-jd-accent focus:ring-jd-accent"
                                                @change="validateEndDate"
                                            >
                                            <span class="ml-2 sm:ml-3 text-xs sm:text-sm text-jd-text font-medium">Non scade mai</span>
                                        </label>
                                        <p v-if="errors.endDate" class="text-xs sm:text-sm text-red-600 font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ errors.endDate }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Preview - Always visible when products selected -->
                            <div v-if="selectedProducts.length > 0" class="bg-jd-title rounded-lg sm:rounded-xl p-3 sm:p-6 border border-jd">
                                <h3 class="text-sm sm:text-lg font-semibold text-jd-white mb-2 sm:mb-4 flex items-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-jd-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Anteprima Prezzi
                                </h3>
                                <p class="text-xs sm:text-sm text-jd-text mb-2 sm:mb-4">
                                    <strong>Nota:</strong> I prezzi vengono arrotondati per difetto (es. €112.50 → €112). 
                                    <span v-if="!selectedPercentage" class="text-orange-600 font-medium">Seleziona una percentuale per vedere i prezzi aggiornati.</span>
                                </p>
                                <div class="space-y-3 max-h-48 overflow-y-auto">
                                    <div 
                                        v-for="product in selectedProducts.slice(0, 10)" 
                                        :key="product.id"
                                        class="flex justify-between items-center p-3 bg-jd-base-lighter rounded-lg border border-jd"
                                    >
                                        <span class="text-jd-text font-medium truncate">{{ product.title }}</span>
                                        <div class="flex items-center space-x-3">
                                            <span class="text-jd-muted line-through">€{{ product.price }}</span>
                                            <span class="font-bold text-orange-600 text-lg">
                                                €{{ selectedPercentage ? calculateDiscountedPrice(product.price) : product.price }}
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="selectedProducts.length > 10" class="text-center text-sm text-jd-muted py-2">
                                        ... e altri {{ selectedProducts.length - 10 }} prodotti
                                    </div>
                                </div>
                            </div>

                            <!-- General Error Messages -->
                            <div v-if="errors.general" class="bg-red-50 border-2 border-red-200 text-red-700 px-4 sm:px-6 py-3 sm:py-4 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="font-semibold mb-1 sm:mb-2 text-sm sm:text-base">Errore di validazione</h4>
                                        <p class="text-sm">{{ errors.general }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Server Error Messages -->
                            <div v-if="error" class="bg-red-50 border-2 border-red-200 text-red-700 px-4 sm:px-6 py-3 sm:py-4 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="font-semibold mb-1 sm:mb-2 text-sm sm:text-base">Errore nell'applicazione dello sconto</h4>
                                        <pre class="text-xs sm:text-sm whitespace-pre-wrap font-mono bg-red-100 p-2 sm:p-3 rounded border overflow-x-auto">{{ error }}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-jd-base-light px-2 sm:px-8 py-2 sm:py-6 flex flex-row items-center justify-between gap-2">
                        <button
                            @click="$emit('close')"
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 text-jd-text bg-jd-title border-2 border-jd rounded-lg hover:bg-jd-base-lighter transition-colors text-xs sm:text-sm font-semibold"
                        >
                            Annulla
                        </button>
                        <button
                            @click="applyDiscount"
                            :disabled="loading || hasErrors"
                            :class="[
                                'flex-1 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all',
                                !loading && !hasErrors
                                    ? 'bg-gradient-jd-accent text-jd-white hover:bg-jd-accent-dark shadow-lg hover:shadow-xl'
                                    : 'bg-jd-title text-jd-muted cursor-not-allowed'
                            ]"
                        >
                            <span v-if="loading" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Salva
                            </span>
                            <span v-else class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Salva
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    selectedProducts: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['close', 'applied']);

const predefinedPercentages = [10, 20, 50];
const selectedPercentage = ref(null);
const customPercentage = ref('');
const startDate = ref('');
const endDate = ref('');
const neverExpires = ref(false);
const loading = ref(false);
const error = ref('');

// Validation errors
const errors = ref({
    percentage: '',
    startDate: '',
    endDate: '',
    general: ''
});

// Get today's date in YYYY-MM-DD format
const getTodayDate = () => {
    const today = new Date();
    return today.toISOString().split('T')[0];
};

// Get minimum date for date inputs (today)
const minDate = getTodayDate();

// Set default start date to now
watch(() => props.show, (show) => {
    if (show) {
        const now = new Date();
        startDate.value = now.toISOString().split('T')[0]; // Only date part
        endDate.value = '';
        neverExpires.value = true; // Default to never expires
        selectedPercentage.value = null;
        customPercentage.value = '';
        error.value = '';
        errors.value = {
            percentage: '',
            startDate: '',
            endDate: '',
            general: ''
        };
    }
});

// Validate start date
const validateStartDate = () => {
    errors.value.startDate = '';
    
    if (!startDate.value) {
        return true; // Start date is optional
    }
    
    const selectedStartDate = new Date(startDate.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedStartDate < today) {
        errors.value.startDate = 'La data di inizio non può essere nel passato.';
        return false;
    }
    
    // Check if end date is before start date (only if end date is set)
    if (endDate.value && !neverExpires.value) {
        const selectedEndDate = new Date(endDate.value);
        if (selectedEndDate < selectedStartDate) {
            errors.value.endDate = 'La data di fine deve essere successiva alla data di inizio.';
            return false;
        }
    }
    
    return true;
};

// Validate end date
const validateEndDate = () => {
    errors.value.endDate = '';
    
    if (neverExpires.value || !endDate.value) {
        return true; // End date is optional or never expires
    }
    
    const selectedEndDate = new Date(endDate.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedEndDate < today) {
        errors.value.endDate = 'La data di fine non può essere nel passato.';
        return false;
    }
    
    // Check if end date is before start date
    if (startDate.value) {
        const selectedStartDate = new Date(startDate.value);
        if (selectedEndDate < selectedStartDate) {
            errors.value.endDate = 'La data di fine deve essere successiva alla data di inizio.';
            return false;
        }
    }
    
    return true;
};

// Validate percentage
const validatePercentage = () => {
    errors.value.percentage = '';
    
    if (!selectedPercentage.value) {
        errors.value.percentage = 'Seleziona una percentuale di sconto.';
        return false;
    }
    
    const percentage = selectedPercentage.value;
    
    if (isNaN(percentage) || percentage < 0 || percentage > 100) {
        errors.value.percentage = 'La percentuale deve essere compresa tra 0 e 100.';
        return false;
    }
    
    return true;
};

// Watch for date changes and validate
watch(startDate, () => {
    validateStartDate();
    // Re-validate end date when start date changes
    if (endDate.value && !neverExpires.value) {
        validateEndDate();
    }
});

watch(endDate, () => {
    validateEndDate();
});

watch(neverExpires, (newVal) => {
    if (newVal) {
        errors.value.endDate = '';
    } else if (endDate.value) {
        validateEndDate();
    }
});

watch([selectedPercentage, customPercentage], () => {
    validatePercentage();
});

// Comprehensive validation before submission
const validateForm = () => {
    let isValid = true;
    
    // Clear all errors
    errors.value = {
        percentage: '',
        startDate: '',
        endDate: '',
        general: ''
    };
    
    // Validate percentage
    if (!validatePercentage()) {
        isValid = false;
    }
    
    // Validate dates
    if (!validateStartDate()) {
        isValid = false;
    }
    
    if (!validateEndDate()) {
        isValid = false;
    }
    
    // Check if products are selected
    if (props.selectedProducts.length === 0) {
        errors.value.general = 'Seleziona almeno un prodotto a cui applicare lo sconto.';
        isValid = false;
    }
    
    return isValid;
};

// Check if there are any validation errors
const hasErrors = computed(() => {
    return errors.value.percentage !== '' || 
           errors.value.startDate !== '' || 
           errors.value.endDate !== '' || 
           errors.value.general !== '';
});

const selectPercentage = (percentage) => {
    selectedPercentage.value = percentage;
    customPercentage.value = '';
};

const selectCustomPercentage = () => {
    if (customPercentage.value !== '') {
        const percentage = parseFloat(customPercentage.value);
        
        // Validate the percentage value
        if (isNaN(percentage) || percentage < 0 || percentage > 100) {
            errors.value.percentage = 'La percentuale deve essere compresa tra 0 e 100.';
            return;
        }
        
        selectedPercentage.value = percentage;
        errors.value.percentage = '';
    } else {
        selectedPercentage.value = null;
    }
};

const calculateDiscountedPrice = (originalPrice) => {
    if (!selectedPercentage.value) return originalPrice;
    return Math.floor(originalPrice * (1 - (selectedPercentage.value / 100)));
};

const applyDiscount = async () => {
    // Validate form before submission
    if (!validateForm()) {
        // Scroll to first error
        const firstErrorField = document.querySelector('.error-field');
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.focus();
        }
        return;
    }

    loading.value = true;
    error.value = '';
    errors.value.general = '';

    try {
        const data = {
            product_ids: props.selectedProducts.map(p => p.id),
            discount_percentage: selectedPercentage.value,
            start_date: startDate.value || null,
            end_date: neverExpires.value ? null : (endDate.value || null),
        };

        console.log('Sending discount data:', data);
        console.log('Selected products:', props.selectedProducts);

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        console.log('CSRF token:', csrfToken ? 'Found' : 'Not found');
        
        const response = await fetch('/admin/discounts/apply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(data),
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', Object.fromEntries(response.headers.entries()));

        if (!response.ok) {
            let errorData;
            try {
                errorData = await response.json();
            } catch {
                const errorText = await response.text();
                throw new Error(`Errore HTTP ${response.status}: ${errorText}`);
            }

            // Handle validation errors from Laravel
            if (errorData.errors) {
                let errorMessages = [];
                
                // Collect all validation errors
                Object.keys(errorData.errors).forEach(key => {
                    const fieldErrors = Array.isArray(errorData.errors[key]) 
                        ? errorData.errors[key] 
                        : [errorData.errors[key]];
                    
                    fieldErrors.forEach(msg => {
                        errorMessages.push(msg);
                    });
                });
                
                // Set appropriate error messages based on field
                if (errorData.errors.start_date) {
                    errors.value.startDate = Array.isArray(errorData.errors.start_date) 
                        ? errorData.errors.start_date[0] 
                        : errorData.errors.start_date;
                }
                
                if (errorData.errors.end_date) {
                    errors.value.endDate = Array.isArray(errorData.errors.end_date) 
                        ? errorData.errors.end_date[0] 
                        : errorData.errors.end_date;
                }
                
                if (errorData.errors.discount_percentage) {
                    errors.value.percentage = Array.isArray(errorData.errors.discount_percentage) 
                        ? errorData.errors.discount_percentage[0] 
                        : errorData.errors.discount_percentage;
                }
                
                error.value = errorMessages.length > 0 
                    ? 'Errori di validazione:\n' + errorMessages.join('\n') 
                    : errorData.message || 'Errore durante l\'applicazione dello sconto.';
                
                return;
            }
            
            throw new Error(errorData.message || `Errore HTTP ${response.status}`);
        }

        const result = await response.json();
        console.log('Response result:', result);

        if (result.success) {
            emit('applied', result);
            emit('close');
            // Reload the page to show updated prices
            window.location.reload();
        } else {
            let errorMessage = result.message || 'Errore durante l\'applicazione dello sconto.';
            
            // Add detailed error information if available
            if (result.error_details) {
                errorMessage += `\n\nDettagli errore:\nFile: ${result.error_details.file}\nLinea: ${result.error_details.line}\nTipo: ${result.error_details.type}`;
            }
            
            if (result.errors && Array.isArray(result.errors)) {
                errorMessage += `\n\nErrori specifici:\n${result.errors.join('\n')}`;
            }
            
            error.value = errorMessage;
        }
    } catch (err) {
        console.error('Error applying discount:', err);
        error.value = 'Errore di connessione. Riprova. ' + err.message;
    } finally {
        loading.value = false;
    }
};
</script>
