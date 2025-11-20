<template>
    <div class="min-h-screen" :class="[isDarkPage ? 'bg-jd-dark' : 'bg-gray-50']" :data-page-component="page.component">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50 safe-area-top">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-14 sm:h-[70px]">
                    <Link :href="route('shop.index')" class="flex items-center flex-shrink-0">
                        <img 
                            src="/images/logo.svg" 
                            alt="JDOutlet" 
                            class="h-20 sm:h-[100px] w-auto"
                        />
                    </Link>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-4">
                        <Link 
                            :href="route('shop.index')" 
                            class="text-lg font-medium px-3 py-2 rounded-md transition-colors"
                            :class="route().current('shop.index') ? 'text-jd-accent' : 'text-gray-700 hover:text-jd-accent'"
                        >
                            Home
                        </Link>
                        <Link 
                            :href="route('shop.philosophy')" 
                            class="text-lg font-medium px-3 py-2 rounded-md transition-colors"
                            :class="route().current('shop.philosophy') ? 'text-jd-accent' : 'text-gray-700 hover:text-jd-accent'"
                        >
                            Filosofia
                        </Link>
                        <Link 
                            :href="route('shop.contacts')" 
                            class="text-lg font-medium px-3 py-2 rounded-md transition-colors"
                            :class="route().current('shop.contacts') ? 'text-jd-accent' : 'text-gray-700 hover:text-jd-accent'"
                        >
                            Contatti
                        </Link>
                        
                        <template v-if="$page.props.auth?.user">
                            <Link 
                                v-if="$page.props.auth.user.is_admin"
                                :href="route('admin.dashboard')" 
                                class="btn-jd-accent px-4 py-2 text-sm"
                            >
                                Admin
                            </Link>
                            <Link 
                                :href="route('logout')" 
                                method="post" 
                                as="button"
                                class="text-sm font-medium text-red-600 hover:text-red-700 transition-colors px-3 py-2 rounded-md active:scale-95"
                            >
                                Esci
                            </Link>
                        </template>
                    </nav>

                    <!-- Mobile menu button -->
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 transition-colors touch-manipulation"
                        aria-label="Menu"
                    >
                        <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div 
                v-if="mobileMenuOpen"
                class="md:hidden border-t border-gray-200 bg-white animate-slide-down"
            >
                <nav class="px-4 py-3 space-y-1">
                    <Link 
                        :href="route('shop.index')" 
                        @click="mobileMenuOpen = false"
                        class="block px-4 py-3 text-base font-medium rounded-lg transition-colors touch-manipulation"
                        :class="route().current('shop.index') ? 'text-jd-accent bg-orange-50' : 'text-gray-700 hover:bg-gray-50'"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Home
                        </div>
                    </Link>
                    <Link 
                        :href="route('shop.philosophy')" 
                        @click="mobileMenuOpen = false"
                        class="block px-4 py-3 text-base font-medium rounded-lg transition-colors touch-manipulation"
                        :class="route().current('shop.philosophy') ? 'text-jd-accent bg-orange-50' : 'text-gray-700 hover:bg-gray-50'"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            Filosofia
                        </div>
                    </Link>
                    <Link 
                        :href="route('shop.contacts')" 
                        @click="mobileMenuOpen = false"
                        class="block px-4 py-3 text-base font-medium rounded-lg transition-colors touch-manipulation"
                        :class="route().current('shop.contacts') ? 'text-jd-accent bg-orange-50' : 'text-gray-700 hover:bg-gray-50'"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contatti
                        </div>
                    </Link>
                    
                    <template v-if="$page.props.auth?.user">
                        <Link 
                            v-if="$page.props.auth.user.is_admin"
                            :href="route('admin.dashboard')" 
                            @click="mobileMenuOpen = false"
                            class="block px-4 py-3 text-base font-medium btn-jd-accent rounded-lg touch-manipulation"
                        >
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Pannello Admin
                            </div>
                        </Link>
                        <Link 
                            :href="route('logout')" 
                            method="post" 
                            as="button"
                            @click="mobileMenuOpen = false"
                            class="block w-full text-left px-4 py-3 text-base font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors active:bg-red-100 touch-manipulation"
                        >
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Esci
                            </div>
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Main content -->
        <main class="safe-area-bottom">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-jd-accent text-white safe-area-bottom border-t border-white/50">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <!-- Left Column -->
                    <div class="lg:border-r lg:border-white/50 p-6 lg:p-8">
                        <div class="mb-4">
                            <img 
                                src="/images/logo.svg" 
                                alt="JDOutlet" 
                                class="h-16 sm:h-24 w-auto mb-4 brightness-0 invert"
                                onerror="this.src='/images/logo.svg'" 
                            />
                            <p class="text-base sm:text-lg leading-tight mb-4 font-light max-w-2xl">
                                JDEvents azienda organizzatrice di eventi apre le porte con il suo primo outlet degli allestimenti. Il luogo dove gli arredi non finiscono mai davvero il loro percorso, dove tu puoi trovare pezzi unici, pronti a ricominciare.
                            </p>
                        </div>

                        <ul class="space-y-2 mb-6">
                            <li class="flex items-start group">
                                <span class="flex-shrink-0 w-2.5 h-2.5 bg-white rounded-full mt-1.5 mr-3"></span>
                                <span class="text-white text-base font-light leading-tight">Materiale venduto "così com'è". Visionabile nel nostro magazzino a Firenze.</span>
                            </li>
                            <li class="flex items-start group">
                                <span class="flex-shrink-0 w-2.5 h-2.5 bg-white rounded-full mt-1.5 mr-3"></span>
                                <span class="text-white text-base font-light leading-tight">Prodotti aggiornati spesso in base agli eventi. Aggiungi il sito ai preferiti!</span>
                            </li>
                            <li class="flex items-start group">
                                <span class="flex-shrink-0 w-2.5 h-2.5 bg-white rounded-full mt-1.5 mr-3"></span>
                                <span class="text-white text-base font-light leading-tight">Modifiche disponibili su richiesta (con costo aggiuntivo).</span>
                            </li>
                            <li class="flex items-start group">
                                <span class="flex-shrink-0 w-2.5 h-2.5 bg-white rounded-full mt-1.5 mr-3"></span>
                                <span class="text-white text-base font-light leading-tight">Ritiro in sede. Spedizioni a carico dell'acquirente.</span>
                            </li>
                        </ul>

                        <button 
                            @click="handleShopAction"
                            class="bg-[#001f3f] text-white hover:bg-[#00152b] font-bold text-base px-6 py-2 rounded-md shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 mb-6 w-full sm:w-auto"
                        >
                            Scopri gli arredi disponibili
                        </button>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-t border-white/20 pt-4">
                            <!-- Socials -->
                            <div>
                                <h3 class="font-bold text-white text-base mb-3">Seguici</h3>
                                <div class="flex space-x-3">
                                    <a href="https://www.facebook.com/JDEventsrl" target="_blank" rel="noopener noreferrer" class="bg-white hover:bg-gray-100 text-jd-accent rounded-full w-8 h-8 flex items-center justify-center transition-all duration-300">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                    <a href="https://www.instagram.com/jdevents/" target="_blank" rel="noopener noreferrer" class="bg-white hover:bg-gray-100 text-jd-accent rounded-full w-8 h-8 flex items-center justify-center transition-all duration-300">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>
                                    <a href="https://www.linkedin.com/company/jd-events-srl" target="_blank" rel="noopener noreferrer" class="bg-white hover:bg-gray-100 text-jd-accent rounded-full w-8 h-8 flex items-center justify-center transition-all duration-300">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    </a>
                                </div>
                            </div>
                            <!-- Useful Links -->
                            <div>
                                <h3 class="font-bold text-white text-base mb-3">Collegamenti utili</h3>
                                <div class="flex flex-col space-y-2">
                                    <a href="https://jdsetup.com/" target="_blank" class="flex items-center text-white/90 hover:text-white transition-colors group">
                                        <span class="w-6 h-6 border border-white/50 rounded-sm flex items-center justify-center mr-2 group-hover:bg-white group-hover:text-jd-accent transition-all duration-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </span>
                                        <span class="text-base font-light">JDSetup</span>
                                    </a>
                                    <a href="https://www.dropbox.com/scl/fo/x8mh4iarwtsfbyknqw4jj/AE6k34LKQ7e27yoBte0nqB4?rlkey=tjkadfgpzdst040n55vhe1465&st=7rizg3d0&dl=0" target="_blank" class="flex items-center text-white/90 hover:text-white transition-colors group">
                                        <span class="w-6 h-6 border border-white/50 rounded-sm flex items-center justify-center mr-2 group-hover:bg-white group-hover:text-jd-accent transition-all duration-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </span>
                                        <span class="text-base font-light">I nostri cataloghi</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="flex flex-col h-full relative p-6 lg:p-8">
                        <div class="text-center mb-4">
                            <h2 class="text-2xl sm:text-3xl font-bold text-[#1a1a1a] mb-2">Vieni a trovarci!</h2>
                            <p class="text-base sm:text-lg text-[#1a1a1a] mb-4 leading-relaxed">Siamo operativi nel nostro magazzino di Firenze.<br>Ti aspettiamo!</p>
                            <p class="text-base sm:text-lg text-[#1a1a1a] font-bold">Ecco dove siamo:</p>
                        </div>
                        <div class="w-full max-w-xl mx-auto h-48 sm:h-60 rounded-lg overflow-hidden shadow-lg bg-white mb-2">
                            <iframe 
                                src="https://maps.google.com/maps?q=JD+Events+srl,+Via+di+Capalle,+46,+50041+Calenzano+FI&z=15&t=k&output=embed" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bottom Divider -->
            <div class="border-t border-white/50"></div>
        </footer>

        <!-- Sub-footer / Legal Bar -->
        <div class="bg-[#092133] text-white text-[10px] sm:text-xs py-6 border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                    <!-- Left Side: Copyright & Certifications -->
                    <div class="flex flex-col space-y-4 max-w-4xl">
                        <p class="opacity-80 leading-relaxed text-center lg:text-left">
                            &copy;2025 Via degli Artigiani, 25 – 50041 Calenzano (FI) – info@jdsetup.com – P.I. e C.F. 05632880489, R.E.A. 561877, Cap. Soc. €130.000,00 i.v. – Realizzato da
                            <a href="https://rifo-dev.it/" target="_blank" rel="noopener noreferrer" class="underline hover:text-jd-accent">
                                Marco Riformato
                            </a>
                        </p>
                        
                        <!-- Certifications -->
                        <div class="flex flex-wrap justify-center lg:justify-start items-center gap-4">
                            <img src="/images/1.webp" alt="Certificazione 1" class="h-12 sm:h-14 w-auto object-contain rounded-sm" loading="lazy" />
                            <img src="/images/2.webp" alt="Certificazione 2" class="h-12 sm:h-14 w-auto object-contain rounded-sm" loading="lazy" />
                            <img src="/images/3.webp" alt="Certificazione 3" class="h-12 sm:h-14 w-auto object-contain rounded-sm" loading="lazy" />
                            <img src="/images/4.webp" alt="Certificazione 4" class="h-12 sm:h-14 w-auto object-contain rounded-sm" loading="lazy" />
                        </div>
                    </div>

                    <!-- Right Side: Links & Accedi -->
                    <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8 whitespace-nowrap">
                        <a href="#" class="hover:text-white/80 transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-white/80 transition-colors">Cookie Policy</a>
                        <Link 
                            :href="route('login')" 
                            class="text-white/60 hover:text-white transition-colors"
                        >
                            Accedi
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    tags: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();

const isDarkPage = computed(() => {
    const component = page.component;
    const url = page.url;
    
    const isShopIndex = component === 'Shop/Index' || url.startsWith('/shop');
    const isShopShow = component === 'Shop/Show' || url.match(/^\/shop\/\d+/);
    const isShopContacts = component === 'Shop/Contacts' || url.startsWith('/contatti');
    const isPhilosophy = component === 'Shop/Philosophy' || url.startsWith('/filosofia');

    return (isShopIndex || isShopShow || isShopContacts) && !isPhilosophy;
});

const mobileMenuOpen = ref(false);

const handleShopAction = () => {
    // Check if we are on the shop index page
    if (route().current('shop.index')) {
        const productsSection = document.getElementById('products');
        if (productsSection) {
            productsSection.scrollIntoView({ behavior: 'smooth' });
        }
    } else {
        router.visit(route('shop.index'));
    }
};
</script>

<style scoped>
@keyframes slide-down {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-down {
    animation: slide-down 0.2s ease-out;
}

.touch-manipulation {
    touch-action: manipulation;
}

/* Safe area support for mobile devices */
.safe-area-top {
    padding-top: env(safe-area-inset-top);
}

.safe-area-bottom {
    padding-bottom: env(safe-area-inset-bottom);
}

.bg-jd-dark {
    background-color: #092133 !important;
}
</style>