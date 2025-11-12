<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Mobile menu button -->
        <div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white shadow-md safe-area-top">
            <div class="flex items-center justify-between px-3 sm:px-4 py-3 h-14">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                    <img 
                        src="/images/logo.svg" 
                        alt="JDOutlet Admin" 
                        class="h-8 sm:h-10 w-auto flex-shrink-0"
                    />
                    <!-- Version Badge on Mobile -->
                    <div class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-1 rounded border border-gray-300 flex-shrink-0">
                        v{{ $page.props.app_version || '0.2' }}
                    </div>
                </div>
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="p-2 rounded-md text-gray-600 hover:bg-gray-100 transition-colors touch-manipulation flex-shrink-0"
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

        <!-- Sidebar -->
        <div 
            :class="['fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 safe-area-top border-r border-gray-200', mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']"
        >
            <div class="flex flex-col h-full overflow-hidden">
                <div class="flex items-center justify-center h-20 lg:h-24 shadow-md flex-shrink-0 bg-jd-button px-4">
                    <img 
                        src="/images/logo.svg" 
                        alt="JDOutlet Admin" 
                        class="h-12 lg:h-16 w-auto"
                        style="filter: brightness(0) invert(1);"
                    />
                </div>

                <nav class="flex-1 px-3 lg:px-4 py-4 lg:py-6 overflow-y-auto overscroll-contain">
                    <Link 
                        :href="route('admin.dashboard')" 
                        @click="mobileMenuOpen = false"
                        class="flex items-center px-3 lg:px-4 py-3 mb-2 rounded-lg hover:bg-gray-50 transition-colors touch-manipulation"
                        :class="route().current('admin.dashboard') ? 'bg-gray-100 link-jd font-medium' : 'text-gray-700'"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-sm lg:text-base">Dashboard</span>
                    </Link>

                    <Link 
                        :href="route('products.create')" 
                        @click="mobileMenuOpen = false"
                        class="flex items-center px-3 lg:px-4 py-3 mb-2 rounded-lg hover:bg-gray-50 transition-colors touch-manipulation"
                        :class="route().current('products.create') ? 'bg-gray-100 link-jd font-medium' : 'text-gray-700'"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="text-sm lg:text-base">Nuovo Prodotto</span>
                    </Link>

                    <!-- Only show Customers for full admins, not editors -->
                    <Link 
                        v-if="$page.props.auth.user.is_full_admin"
                        :href="route('customers.index')" 
                        @click="mobileMenuOpen = false"
                        class="flex items-center px-3 lg:px-4 py-3 mb-2 rounded-lg hover:bg-gray-50 transition-colors touch-manipulation"
                        :class="route().current('customers.*') ? 'bg-gray-100 link-jd font-medium' : 'text-gray-700'"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-sm lg:text-base">Clienti</span>
                    </Link>

                    <Link 
                        :href="route('shop.index')" 
                        @click="mobileMenuOpen = false"
                        class="flex items-center px-3 lg:px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors touch-manipulation"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span class="text-sm lg:text-base">Catalogo</span>
                    </Link>
                </nav>

                <div class="flex-shrink-0 border-t border-gray-200 p-4">
                    <!-- User info -->
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $page.props.auth.user.name }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $page.props.auth.user.email }}
                        </p>
                    </div>

                    <!-- Version Badge (Desktop) -->
                    <div class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-1 rounded border border-gray-300 mb-3 text-center hidden lg:block">
                        v{{ $page.props.app_version || '0.2' }}
                    </div>

                    <!-- Logout button -->
                    <Link 
                        :href="route('logout')" 
                        method="post" 
                        as="button"
                        @click="mobileMenuOpen = false"
                        class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors active:bg-red-100 touch-manipulation"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Esci
                    </Link>
                </div>
            </div>
        </div>

        <!-- Mobile overlay -->
        <div 
            v-if="mobileMenuOpen"
            @click="mobileMenuOpen = false"
            class="fixed inset-0 z-30 bg-black bg-opacity-50 lg:hidden animate-fade-in"
        ></div>

        <!-- Main content -->
        <main class="lg:pl-64 pt-14 lg:pt-0 flex-grow">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <slot />
            </div>
        </main>

        <!-- Footer -->
        <footer class="lg:pl-64 bg-jd-section text-white mt-8 safe-area-bottom flex-shrink-0">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <p class="text-jd text-xs sm:text-sm">
                        &copy; {{ new Date().getFullYear() }} JDOutlet by JDEvents. Tutti i diritti riservati.
                    </p>
                    <div class="flex space-x-4">
                        <a 
                            href="https://www.facebook.com" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="w-8 h-8 bg-white/10 hover:bg-jd-accent rounded-full flex items-center justify-center transition-colors"
                            aria-label="Facebook"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a 
                            href="https://www.instagram.com" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="w-8 h-8 bg-white/10 hover:bg-jd-accent rounded-full flex items-center justify-center transition-colors"
                            aria-label="Instagram"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-fade-in {
    animation: fade-in 0.2s ease-out;
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
