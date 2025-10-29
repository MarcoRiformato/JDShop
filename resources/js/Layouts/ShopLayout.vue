<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50 safe-area-top">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-14 sm:h-16">
                    <Link :href="route('shop.index')" class="flex items-center flex-shrink-0">
                        <h1 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            JDOutlet
                        </h1>
                    </Link>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-4">
                        <Link 
                            :href="route('shop.index')" 
                            class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors px-3 py-2 rounded-md"
                            :class="{ 'text-blue-600 bg-blue-50': route().current('shop.index') }"
                        >
                            Catalogo
                        </Link>
                        
                        <template v-if="$page.props.auth?.user">
                            <Link 
                                v-if="$page.props.auth.user.is_admin"
                                :href="route('admin.dashboard')" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors active:scale-95"
                            >
                                Admin
                            </Link>
                            <Link 
                                :href="route('logout')" 
                                method="post" 
                                as="button"
                                class="text-sm font-medium text-gray-700 hover:text-red-600 transition-colors px-3 py-2 rounded-md active:scale-95"
                            >
                                Esci
                            </Link>
                        </template>
                        <template v-else>
                            <Link 
                                :href="route('login')" 
                                class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors px-3 py-2 rounded-md active:scale-95"
                            >
                                Accedi
                            </Link>
                        </template>
                    </nav>

                    <!-- Mobile menu button -->
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition-colors touch-manipulation"
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
                        class="block px-4 py-3 text-base font-medium text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors active:bg-blue-100 touch-manipulation"
                        :class="{ 'bg-blue-50 text-blue-600': route().current('shop.index') }"
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
                            class="block px-4 py-3 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors active:bg-blue-800 touch-manipulation"
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
                            class="block px-4 py-3 text-base font-medium text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors active:bg-blue-100 touch-manipulation"
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
        <footer class="bg-white border-t border-gray-200 mt-12 safe-area-bottom">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <div class="text-center text-gray-500 text-xs sm:text-sm">
                    <p>&copy; {{ new Date().getFullYear() }} JDOutlet. Tutti i diritti riservati.</p>
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

