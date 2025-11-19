<template>
    <Head title="La nostra filosofia">
        <meta name="description" content="La nostra filosofia del Riuso: meno sprechi, più creatività, più responsabilità.">
    </Head>
    <ShopLayout>
        <!-- Section 1: La nostra filosofia del Riuso -->
        <div class="relative overflow-hidden bg-[#092133] min-h-[600px] flex items-center">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img 
                    src="/images/scheda2.jpg" 
                    alt="Filosofia del Riuso" 
                    class="w-full h-full object-cover object-right"
                />
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#092133] from-10% via-[#092133]/95 via-40% to-transparent"></div>
            </div>

            <!-- Content -->
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
                <div class="max-w-2xl">
                    <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-bold text-white mb-2 leading-[1.2]">
                        La nostra filosofia<br>del Riuso
                    </h1>
                    
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#ff4400] mb-8">
                        Una scelta consapevole.
                    </h2>
                    
                    <p class="text-base sm:text-lg text-white/95 mb-6 leading-relaxed">
                        I materiali che usiamo negli allestimenti cambiano<br class="hidden sm:block">
                        spesso<br class="hidden sm:block">
                        — non perché non siano più validi, ma perché ogni<br class="hidden sm:block">
                        progetto richiede uno stile diverso.<br class="hidden sm:block">
                        Ciò che per noi conclude un ciclo resta comunque<br class="hidden sm:block">
                        solido, funzionale e pronto a raccontare qualcos'altro.
                    </p>

                    <p class="text-base sm:text-lg text-white/95 mb-6 leading-relaxed">
                        Dare una seconda vita a questi pezzi significa<br class="hidden sm:block">
                        valorizzare ciò che esiste già:<br class="hidden sm:block">
                        <span class="italic font-semibold">meno sprechi, più creatività, più responsabilità.</span><br class="hidden sm:block">
                        Ogni arredo può diventare perfetto per un nuovo spazio,<br class="hidden sm:block">
                        una nuova persona, una nuova storia.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 2: Un'occasione per te! -->
        <div class="bg-[#092133] py-16 sm:py-24 border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-[#ff4400] mb-6">
                            Un'occasione per te !
                        </h2>
                        
                        <p class="text-lg text-white mb-6 leading-relaxed">
                            Dare una seconda vita significa fare spazio a nuove<br>
                            storie nei tuoi ambienti, nei tuoi progetti, nel tuo<br>
                            quotidiano.
                        </p>

                        <p class="text-lg text-white mb-8 leading-relaxed">
                            <span class="italic font-semibold">È una scelta consapevole</span> perché riusare non significa<br>
                            accontentarsi, <span class="italic font-semibold">significa scegliere meglio.</span>
                        </p>
                    </div>

                    <div>
                        <ul class="space-y-6 mb-12">
                            <li class="flex items-center text-white text-xl sm:text-2xl">
                                <span class="text-[#ff4400] mr-4 text-2xl">→</span>
                                Prezzi più accessibili.
                            </li>
                            <li class="flex items-center text-white text-xl sm:text-2xl">
                                <span class="text-[#ff4400] mr-4 text-2xl">→</span>
                                Qualità professionale e testata sul campo
                            </li>
                            <li class="flex items-center text-white text-xl sm:text-2xl">
                                <span class="text-[#ff4400] mr-4 text-2xl">→</span>
                                Uno stile unico, con carattere e storia
                            </li>
                        </ul>

                        <div class="text-center lg:text-left">
                            <Link 
                                :href="route('shop.index') + '#products'"
                                class="inline-block bg-[#ff4400] hover:bg-[#ff4400]/90 text-white font-semibold px-8 py-4 rounded-md transition-all text-lg shadow-lg hover:shadow-xl"
                            >
                                Scopri i prodotti disponibili
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ShopLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
</script>
