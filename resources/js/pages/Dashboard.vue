<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import StatCard from '../components/StatCard.vue';

const stats = ref({
    total_products: 0,
    total_orders: 0,
});

const fetchStats = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/dashboard/stats', {
            headers: { Authorization: `Bearer ${token}` },
        });
        stats.value = response.data;
    } catch (error) {
        console.error('Error fetching stats:', error);
    }
};

onMounted(() => {
    fetchStats();
});
</script>

<template>
    <div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">Dashboard</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Total Products Card -->
            <StatCard 
                title="Total Products" 
                :value="stats.total_products"
                icon-class="text-blue-600 dark:text-blue-300"
                icon-bg-class="bg-blue-100 dark:bg-blue-900"
            >
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </template>
            </StatCard>

            <!-- Total Orders Card -->
            <StatCard 
                title="Total Orders" 
                :value="stats.total_orders"
                icon-class="text-green-600 dark:text-green-300"
                icon-bg-class="bg-green-100 dark:bg-green-900"
            >
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </template>
            </StatCard>
        </div>
    </div>
</template>
