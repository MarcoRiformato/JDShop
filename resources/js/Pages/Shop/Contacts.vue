<template>
    <Head title="Contatti">
        <meta name="description" content="Contattaci per maggiori informazioni sui nostri arredi e allestimenti.">
    </Head>
    <ShopLayout>
        <!-- Hero / Contact Summary -->
        <section class="relative bg-[#092133] text-white overflow-hidden">
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.15), transparent 45%), radial-gradient(circle at 80% 0%, rgba(255,68,0,0.2), transparent 40%);"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-white/70 mb-4">Parla con noi</p>
                        <h1 class="text-4xl sm:text-5xl font-bold leading-tight mb-6">Il primo outlet degli allestimenti JDEvents</h1>
                        <p class="text-lg text-white/90 mb-8">
                            Quante volte avete visto foto di eventi su una rivista o sui social e avete pensato: "WOW! Che spettacolo!!!"?
                            Ecco, un po’ di quella piccola meraviglia potrebbe essere vostra.
                        </p>
                        <p class="text-lg text-white/90 mb-10">
                            Tavoli, sedie, arredamenti di varia natura e pezzi unici creati ad hoc per sfilate, fiere, meeting ed eventi in genere.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="bg-white/10 border border-white/10 rounded-xl p-5 backdrop-blur">
                                <div class="text-[#ff4400] mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                </div>
                                <p class="text-sm uppercase tracking-wide text-white/70">Email</p>
                                <a href="mailto:info@jdsetup.com" class="text-xl font-semibold text-white hover:text-[#ff4400] transition-colors">info@jdsetup.com</a>
                            </div>
                            <div class="bg-white/10 border border-white/10 rounded-xl p-5 backdrop-blur">
                                <div class="text-[#ff4400] mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                </div>
                                <p class="text-sm uppercase tracking-wide text-white/70">Telefono</p>
                                <a href="tel:+39055433349" class="text-xl font-semibold text-white hover:text-[#ff4400] transition-colors">055 433349</a>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-white/20">
                        <iframe 
                            src="https://maps.google.com/maps?q=JD+Events+srl,+Via+di+Capalle,+46,+50041+Calenzano+FI&hl=it&z=15&t=k&output=embed"
                            width="100%" 
                            height="320" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="JD Events srl - Via di Capalle, 46, 50041 Calenzano FI"
                        ></iframe>
                        <div class="p-6 bg-white">
                            <h3 class="text-xl font-semibold text-jd-section mb-2">JD Events srl</h3>
                            <p class="text-gray-600">
                                Via di Capalle, 46<br>
                                50041 Calenzano (FI) · Italia
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Info + Form -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                            <p class="text-sm uppercase tracking-[0.4em] text-gray-400 mb-3">Vieni a trovarci</p>
                            <h2 class="text-3xl font-semibold text-jd-section mb-6">Ti aspettiamo a Calenzano</h2>
                            <p class="text-gray-600 mb-6">
                                Il materiale viene aggiornato ed aumentato in base agli eventi realizzati. Salvati il sito e ogni tanto butta un occhio!
                            </p>
                            <ul class="space-y-5 text-gray-700">
                                <li class="flex items-start">
                                    <span class="text-[#ff4400] mr-3 mt-0.5">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span>
                                        <strong>Materiale visionabile</strong> presso il nostro magazzino di Firenze nello stato di fatto in cui si trova.
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#ff4400] mr-3 mt-0.5">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span>
                                        <strong>Ritiro in sede</strong>. Eventuali spese di spedizione sono a carico dell'acquirente.
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#ff4400] mr-3 mt-0.5">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span>
                                        <strong>Personalizzazioni disponibili</strong>. Se hai trovato un pezzo che ti piace ma in formato diverso, chiedici se possiamo modificarlo (costo aggiuntivo).
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8">
                            <p class="text-sm uppercase tracking-[0.3em] text-gray-400 mb-2">Scrivici</p>
                            <h2 class="text-2xl font-bold text-jd-section mb-6">Inviaci un messaggio</h2>
                            
                            <div v-if="form.recentlySuccessful" class="bg-green-50 border-2 border-green-500 rounded-xl p-8 text-center animate-fade-in shadow-lg">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Messaggio Inviato!</h3>
                                <p class="text-lg text-gray-600">
                                    Grazie per averci contattato. Abbiamo ricevuto la tua richiesta e ti risponderemo al più presto all'indirizzo email indicato.
                                </p>
                                <button 
                                    @click="form.recentlySuccessful = false" 
                                    class="mt-8 px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                >
                                    Invia un altro messaggio
                                </button>
                            </div>

                            <form v-else @submit.prevent="submit" class="space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Nome e Cognome</label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        v-model="form.name"
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-white text-gray-900 focus:border-jd-accent focus:ring-2 focus:ring-jd-accent/20 transition-all shadow-sm placeholder-gray-400"
                                        placeholder="Il tuo nome"
                                        required
                                    >
                                    <div v-if="form.errors.name" class="text-red-600 text-sm mt-1 font-medium">{{ form.errors.name }}</div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Indirizzo Email</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        v-model="form.email"
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-white text-gray-900 focus:border-jd-accent focus:ring-2 focus:ring-jd-accent/20 transition-all shadow-sm placeholder-gray-400"
                                        placeholder="tua@email.com"
                                        required
                                    >
                                    <div v-if="form.errors.email" class="text-red-600 text-sm mt-1 font-medium">{{ form.errors.email }}</div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">Telefono <span class="text-gray-400 font-normal">(opzionale)</span></label>
                                        <input 
                                            type="tel" 
                                            id="phone" 
                                            v-model="form.phone"
                                            class="w-full px-4 py-3 rounded-lg border-gray-300 bg-white text-gray-900 focus:border-jd-accent focus:ring-2 focus:ring-jd-accent/20 transition-all shadow-sm placeholder-gray-400"
                                            placeholder="+39 ..."
                                        >
                                        <div v-if="form.errors.phone" class="text-red-600 text-sm mt-1 font-medium">{{ form.errors.phone }}</div>
                                    </div>

                                    <div>
                                        <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">Cosa cerchi?</label>
                                        <div class="relative">
                                            <select 
                                                id="category" 
                                                v-model="form.category"
                                                class="w-full px-4 py-3 rounded-lg border-gray-300 bg-white text-gray-900 focus:border-jd-accent focus:ring-2 focus:ring-jd-accent/20 transition-all appearance-none cursor-pointer shadow-sm"
                                            >
                                                <option value="" disabled>Seleziona categoria</option>
                                                <option value="audio">Audio</option>
                                                <option value="luci">Luci</option>
                                                <option value="video">Video</option>
                                                <option value="arredi">Arredi</option>
                                                <option value="abbigliamento">Abbigliamento</option>
                                                <option value="altro">Altro</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </div>
                                        </div>
                                        <div v-if="form.errors.category" class="text-red-600 text-sm mt-1 font-medium">{{ form.errors.category }}</div>
                                    </div>
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-semibold text-gray-900 mb-2">Messaggio</label>
                                    <textarea 
                                        id="message" 
                                        v-model="form.message"
                                        rows="5" 
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 bg-white text-gray-900 focus:border-jd-accent focus:ring-2 focus:ring-jd-accent/20 transition-all shadow-sm resize-none placeholder-gray-400"
                                        placeholder="Scrivi qui la tua richiesta..."
                                        required
                                    ></textarea>
                                    <div v-if="form.errors.message" class="text-red-600 text-sm mt-1 font-medium">{{ form.errors.message }}</div>
                                </div>

                                <div class="flex items-start p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center h-5">
                                        <input 
                                            id="gdpr_consent" 
                                            v-model="form.gdpr_consent"
                                            type="checkbox" 
                                            required
                                            class="h-5 w-5 text-jd-accent border-gray-300 rounded focus:ring-jd-accent transition-colors cursor-pointer"
                                        >
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="gdpr_consent" class="font-medium text-gray-900 cursor-pointer">Acconsento al trattamento dei dati personali</label>
                                        <p class="text-gray-600 mt-1">I tuoi dati saranno trattati in conformità con la nostra Privacy Policy per gestire la tua richiesta.</p>
                                        <div v-if="form.errors.gdpr_consent" class="text-red-600 text-sm mt-1 font-medium">{{ form.errors.gdpr_consent }}</div>
                                    </div>
                                </div>

                                <div>
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing"
                                        class="w-full flex justify-center items-center py-4 px-6 rounded-lg text-lg font-bold text-white bg-jd-accent hover:bg-[#e63f1f] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jd-accent disabled:opacity-60 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                    >
                                        <span v-if="form.processing">Invio in corso...</span>
                                        <span v-else class="flex items-center">
                                            Invia richiesta
                                            <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </ShopLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    category: '',
    message: '',
    gdpr_consent: false,
});

const submit = () => {
    form.post(route('contact.send'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>
