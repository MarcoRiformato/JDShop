<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50 safe-area-top">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20 sm:h-24">
                    <Link :href="route('shop.index')" class="flex items-center flex-shrink-0">
                        <img 
                            src="/images/logo.svg" 
                            alt="JDOutlet" 
                            class="h-16 sm:h-20 w-auto"
                        />
                    </Link>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-4">
                        <Link 
                            :href="route('shop.index')" 
                            class="text-lg font-medium link-jd px-3 py-2 rounded-md"
                        >
                            Catalogo
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
                        <template v-else>
                            <Link 
                                :href="route('login')" 
                                class="text-lg font-medium link-jd px-3 py-2 rounded-md active:scale-95"
                            >
                                Accedi
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
                        class="block px-4 py-3 text-base font-medium link-jd rounded-lg hover:bg-gray-50 transition-colors touch-manipulation"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Catalogo
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
                    <template v-else>
                        <Link 
                            :href="route('login')" 
                            @click="mobileMenuOpen = false"
                            class="block px-4 py-3 text-base font-medium link-jd rounded-lg hover:bg-gray-50 transition-colors touch-manipulation"
                        >
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Accedi
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
        <footer class="bg-jd-section text-white safe-area-bottom">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Social Media Links -->
                    <div>
                        <h3 class="text-white font-semibold text-base sm:text-lg mb-4">Seguici</h3>
                        <div class="flex space-x-4">
                            <a 
                                href="https://www.facebook.com" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="w-10 h-10 bg-white/10 hover:bg-jd-accent rounded-full flex items-center justify-center transition-colors"
                                aria-label="Facebook"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a 
                                href="https://www.instagram.com" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="w-10 h-10 bg-white/10 hover:bg-jd-accent rounded-full flex items-center justify-center transition-colors"
                                aria-label="Instagram"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links - Categories/Tags -->
                    <div class="sm:col-span-2">
                        <h3 class="text-white font-semibold text-base sm:text-lg mb-4">Categorie</h3>
                        <div v-if="tags && tags.length > 0" class="flex flex-wrap gap-2">
                            <Link 
                                v-for="tag in tags" 
                                :key="tag"
                                :href="route('shop.index', { tag: tag })"
                                class="text-jd hover:text-jd-accent transition-colors text-sm"
                            >
                                {{ tag }}
                            </Link>
                        </div>
                        <p v-else class="text-jd text-sm">Nessuna categoria disponibile</p>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="border-t border-gray-700 mt-8 pt-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 text-center sm:text-left">
                        <p class="text-jd text-xs sm:text-sm">
                            &copy; {{ new Date().getFullYear() }} JDOutlet by JDEvents. Tutti i diritti riservati.
                        </p>
                        <div class="flex flex-wrap justify-center gap-4 sm:gap-6 text-jd text-xs sm:text-sm">
                            <a href="#" class="hover:text-jd-accent transition-colors">Privacy Policy</a>
                            <a href="#" class="hover:text-jd-accent transition-colors">Termini e Condizioni</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    tags: {
        type: Array,
        default: () => [],
    },
});

const mobileMenuOpen = ref(false);
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
</style>

