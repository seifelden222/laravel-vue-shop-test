<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
    email: '',
    password: '',
});

const error = ref('');
const loading = ref(false);

const login = async () => {
    loading.value = true;
    error.value = '';
    try {
        const response = await axios.post('/api/auth/login', form.value);
        const token = response.data.token;
        localStorage.setItem('token', token);
        router.push('/products'); // Redirect to products/dashboard
    } catch (e: any) {
        if (e.response && e.response.data && e.response.data.message) {
            error.value = e.response.data.message;
        } else {
            error.value = 'An error occurred during login.';
        }
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div
        class="flex min-h-screen items-center justify-center bg-gray-100 dark:bg-gray-900"
    >
        <div
            class="w-full max-w-md rounded-lg bg-white p-8 shadow-md dark:bg-gray-800"
        >
            <h2
                class="mb-6 text-center text-2xl font-bold text-gray-900 dark:text-white"
            >
                Login
            </h2>

            <form @submit.prevent="login" class="space-y-4">
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Email</label
                    >
                    <input
                        v-model="form.email"
                        type="email"
                        id="email"
                        required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <div>
                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Password</label
                    >
                    <input
                        v-model="form.password"
                        type="password"
                        id="password"
                        required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <div
                    v-if="error"
                    class="text-sm text-red-600 dark:text-red-400"
                >
                    {{ error }}
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none disabled:opacity-50"
                >
                    {{ loading ? 'Logging in...' : 'Login' }}
                </button>
            </form>

            <div class="mt-4 text-center text-sm">
                <p class="text-gray-600 dark:text-gray-400">
                    Don't have an account?
                    <router-link
                        to="/register"
                        class="font-medium text-indigo-600 hover:text-indigo-500"
                        >Register</router-link
                    >
                </p>
            </div>
        </div>
    </div>
</template>
