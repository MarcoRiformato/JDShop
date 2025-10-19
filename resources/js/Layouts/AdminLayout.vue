<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Mobile menu button -->
        <div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white shadow-md">
            <div class="flex items-center justify-between p-4">
                <h1 class="text-xl font-bold text-gray-900">JD Events Admin</h1>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-md hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div 
            :class="['fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg transform transition-transform duration-300 lg:translate-x-0', mobileMenuOpen ? 'translate-x-0' : '-translate-x-full']"
        >
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-center h-20 shadow-md">
                    <h1 class="text-2xl font-bold text-blue-600">JD Events Admin</h1>
                </div>

                <nav class="flex-1 px-4 py-6 overflow-y-auto">
                    <Link 
                        :href="route('admin.dashboard')" 
                        class="flex items-center px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors"
                        :class="{ 'bg-blue-100 text-blue-600': route().current('admin.dashboard') }"
                    >
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </Link>

                    <Link 
                        :href="route('products.create')" 
                        class="flex items-center px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors"
                        :class="{ 'bg-blue-100 text-blue-600': route().current('products.create') }"
                    >
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Product
                    </Link>

                    <Link 
                        :href="route('shop.index')" 
                        class="flex items-center px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        View Shop
                    </Link>
                </nav>

                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center mb-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $page.props.auth?.user?.name }}</p>
                            <p class="text-xs text-gray-500">{{ $page.props.auth?.user?.email }}</p>
                        </div>
                    </div>
                    <Link 
                        :href="route('logout')" 
                        method="post" 
                        as="button"
                        class="w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                    >
                        Logout
                    </Link>
                </div>
            </div>
        </div>

        <!-- Mobile menu overlay -->
        <div 
            v-if="mobileMenuOpen" 
            @click="mobileMenuOpen = false"
            class="fixed inset-0 z-30 bg-black bg-opacity-50 lg:hidden"
        ></div>

        <!-- Main content -->
        <div class="lg:pl-64 pt-16 lg:pt-0">
            <main class="p-6">
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

