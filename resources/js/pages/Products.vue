<script setup lang="ts">
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = () => {
    localStorage.removeItem('token');
    router.push('/login');
};
</script>

<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">Products Dashboard</h1>
        <p>Welcome! You are logged in.</p>
        <button @click="logout" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Logout</button>
    </div>
</template>
