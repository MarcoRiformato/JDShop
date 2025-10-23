<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        JD Eventi
                    </h1>
                    <p class="text-gray-600 mt-2">Accedi al tuo account</p>
                </div>

                <form @submit.prevent="submit">
                    <div class="space-y-4">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input 
                                id="email"
                                v-model="form.email" 
                                type="email" 
                                required
                                autofocus
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.email }"
                            >
                            <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <input 
                                id="password"
                                v-model="form.password" 
                                type="password" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': form.errors.password }"
                            >
                            <p v-if="form.errors.password" class="text-sm text-red-600 mt-1">
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Remember me -->
                        <div class="flex items-center">
                            <input 
                                id="remember"
                                v-model="form.remember" 
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="remember" class="ml-2 text-sm text-gray-700">
                                Ricordami
                            </label>
                        </div>

                        <!-- Submit button -->
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed font-semibold shadow-lg"
                        >
                            {{ form.processing ? 'Accesso in corso...' : 'Accedi' }}
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <Link 
                        :href="route('shop.index')" 
                        class="text-sm text-blue-600 hover:text-blue-700"
                    >
                        ← Torna al catalogo
                    </Link>
                </div>

                <!-- Demo credentials -->
                <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs font-semibold text-blue-900 mb-2">Credenziali Demo:</p>
                    <p class="text-xs text-blue-800">
                        <strong>Admin:</strong> admin@jdshop.com / password<br>
                        <strong>Manager:</strong> manager@jdshop.com / password<br>
                        <strong>Editor:</strong> editor@jdshop.com / password
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

