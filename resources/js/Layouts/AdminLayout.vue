<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Mobile menu button -->
        <div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white shadow-md safe-area-top">
            <div class="flex items-center justify-between px-4 py-3 h-14">
                <h1 class="text-lg font-bold text-gray-900">JDOutlet Admin</h1>
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="p-2 rounded-md text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition-colors touch-manipulation"
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
            :class="['fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 safe-area-top', mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']"
        >
            <div class="flex flex-col h-full overflow-hidden">
                <div class="flex items-center justify-center h-16 lg:h-20 shadow-md flex-shrink-0">
                    <h1 class="text-xl lg:text-2xl font-bold text-blue-600">JDOutlet Admin</h1>
                </div>

                <nav class="flex-1 px-3 lg:px-4 py-4 lg:py-6 overflow-y-auto overscroll-contain">
                    <Link 
                        :href="route('admin.dashboard')" 
                        @click="mobileMenuOpen = false"
                        class="flex items-center px-3 lg:px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors active:bg-blue-100 touch-manipulation"
                        :class="{ 'bg-blue-100 text-blue-600 font-medium': route().current('admin.dashboard') }"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-sm lg:text-base">Dashboard</span>
                    </Link>

                    <Link 
                        :href="route('products.create')" 
                        @click="mobileMenuOpen = false"
                        class="flex items-center px-3 lg:px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors active:bg-blue-100 touch-manipulation"
                        :class="{ 'bg-blue-100 text-blue-600 font-medium': route().current('products.create') }"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="text-sm lg:text-base">Nuovo Prodotto</span>
                    </Link>

                    <Link 
                        :href="route('shop.index')" 
                        @click="mobileMenuOpen = false"
                        class="flex items-center px-3 lg:px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors active:bg-blue-100 touch-manipulation"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span class="text-sm lg:text-base">Vedi Catalogo</span>
                    </Link>
                </nav>

                <div class="flex-shrink-0 border-t border-gray-200">
                    <!-- Version Badge -->
                    <div class="px-3 lg:px-4 py-3">
                        <div class="text-xs text-gray-500 font-mono bg-gray-50 px-2 py-1 rounded border text-center">
                            v{{ $page.props.app_version || '0.2' }}
                        </div>
                    </div>

                    <!-- User info and logout -->
                    <div class="px-3 lg:px-4 py-3 lg:py-4 border-t border-gray-200">
                        <div class="flex items-center mb-3 lg:mb-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $page.props.auth?.user?.name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $page.props.auth?.user?.email }}</p>
                            </div>
                        </div>
                        <Link 
                            :href="route('logout')" 
                            method="post" 
                            as="button"
                            @click="mobileMenuOpen = false"
                            class="w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors active:bg-red-100 touch-manipulation flex items-center justify-center"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Esci
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu overlay -->
        <div 
            v-if="mobileMenuOpen" 
            @click="mobileMenuOpen = false"
            class="fixed inset-0 z-30 bg-black bg-opacity-50 lg:hidden transition-opacity duration-300"
        ></div>

        <!-- Main content -->
        <div class="lg:pl-64 pt-14 lg:pt-0 safe-area-bottom">
            <main class="p-4 sm:p-6 safe-area-bottom">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);
</script>

<style scoped>
.touch-manipulation {
    touch-action: manipulation;
}

.overscroll-contain {
    overscroll-behavior: contain;
}

/* Safe area support for mobile devices */
.safe-area-top {
    padding-top: env(safe-area-inset-top);
}

.safe-area-bottom {
    padding-bottom: env(safe-area-inset-bottom);
}
</style>

