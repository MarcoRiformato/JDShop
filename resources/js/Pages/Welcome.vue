<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
        <div class="max-w-2xl w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-bold text-gray-900 mb-4">
                        Welcome to JD Shop
                    </h1>
                    <p class="text-xl text-gray-600">
                        Laravel {{ laravelVersion }} + Vue 3 + Inertia.js + Tailwind CSS
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                        <div class="text-3xl mb-2">📚</div>
                        <h3 class="font-semibold text-gray-900 mb-2">Documentation</h3>
                        <p class="text-gray-600 text-sm">
                            Learn how to build amazing applications with Laravel and Vue.
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                        <div class="text-3xl mb-2">🚀</div>
                        <h3 class="font-semibold text-gray-900 mb-2">Quick Start</h3>
                        <p class="text-gray-600 text-sm">
                            Get started building your shop with Vue components.
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                        <div class="text-3xl mb-2">🎨</div>
                        <h3 class="font-semibold text-gray-900 mb-2">Tailwind CSS</h3>
                        <p class="text-gray-600 text-sm">
                            Beautiful, responsive design with utility-first CSS.
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 border border-orange-200">
                        <div class="text-3xl mb-2">⚡</div>
                        <h3 class="font-semibold text-gray-900 mb-2">Vite</h3>
                        <p class="text-gray-600 text-sm">
                            Lightning-fast development with hot module replacement.
                        </p>
                    </div>
                </div>

                <div class="text-center">
                    <button 
                        @click="counter++" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105"
                    >
                        Clicked {{ counter }} times
                    </button>
                </div>
            </div>

            <div class="mt-6 text-center text-gray-500 text-sm">
                <p>PHP {{ phpVersion }} • Vue 3 is working! 🎉</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
    laravelVersion: String,
    phpVersion: String,
});

const counter = ref(0);
</script>

