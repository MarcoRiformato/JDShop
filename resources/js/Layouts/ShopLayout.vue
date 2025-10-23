<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <Link :href="route('shop.index')" class="flex items-center">
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            JD Eventi
                        </h1>
                    </Link>

                    <nav class="flex items-center space-x-4">
                        <Link 
                            :href="route('shop.index')" 
                            class="text-gray-700 hover:text-blue-600 transition-colors"
                        >
                            Catalogo
                        </Link>
                        
                        <template v-if="$page.props.auth?.user">
                            <Link 
                                v-if="$page.props.auth.user.is_admin"
                                :href="route('admin.dashboard')" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Pannello Admin
                            </Link>
                            <Link 
                                :href="route('logout')" 
                                method="post" 
                                as="button"
                                class="text-gray-700 hover:text-red-600 transition-colors"
                            >
                                Esci
                            </Link>
                        </template>
                        <template v-else>
                            <Link 
                                :href="route('login')" 
                                class="text-gray-700 hover:text-blue-600 transition-colors"
                            >
                                Accedi
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center text-gray-500 text-sm">
                    <p>&copy; {{ new Date().getFullYear() }} JD Eventi. Tutti i diritti riservati.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

