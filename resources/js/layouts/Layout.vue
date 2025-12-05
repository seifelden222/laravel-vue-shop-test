<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = () => {
    localStorage.removeItem('token');
    router.push('/login');
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-md">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">E-Shop</h1>
            </div>
            <nav class="mt-6">
                <router-link
                    to="/dashboard"
                    class="block px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    active-class="bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 border-r-4 border-indigo-600"
                >
                    Dashboard
                </router-link>
                <router-link
                    to="/products"
                    class="block px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    active-class="bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 border-r-4 border-indigo-600"
                >
                    Products
                </router-link>
                <router-link
                    to="/orders"
                    class="block px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    active-class="bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 border-r-4 border-indigo-600"
                >
                    Orders
                </router-link>
                <button
                    @click="logout"
                    class="w-full text-left px-6 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                >
                    Logout
                </button>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <router-view></router-view>
        </main>
    </div>
</template>
