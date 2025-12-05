<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Modal from '../components/Modal.vue';

interface OrderItem {
    id: number;
    product: {
        name: string;
    };
    quantity: number;
    price: number;
    subtotal: number;
}

interface Order {
    id: number;
    order_number: string;
    total: number;
    status: string;
    created_at: string;
    items: OrderItem[];
}

const orders = ref<Order[]>([]);
const selectedOrder = ref<Order | null>(null);
const showModal = ref(false);

const fetchOrders = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/orders', {
            headers: { Authorization: `Bearer ${token}` },
        });
        orders.value = response.data.orders;
    } catch (error) {
        console.error('Error fetching orders:', error);
    }
};

const viewOrder = (order: Order) => {
    selectedOrder.value = order;
    showModal.value = true;
};

onMounted(() => {
    fetchOrders();
});
</script>

<template>
    <div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Orders Management</h2>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="order in orders" :key="order.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ order.order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ new Date(order.created_at).toLocaleDateString() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${{ order.total }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ order.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="viewOrder(order)" class="text-indigo-600 hover:text-indigo-900">View Details</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Order Details Modal -->
        <Modal :show="showModal && !!selectedOrder" :title="`Order Details: ${selectedOrder?.order_number}`" @close="showModal = false">
            <div v-if="selectedOrder">
                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Date: <span class="font-medium text-gray-900 dark:text-white">{{ new Date(selectedOrder.created_at).toLocaleString() }}</span></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Status: <span class="font-medium text-gray-900 dark:text-white">{{ selectedOrder.status }}</span></p>
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="item in selectedOrder.items" :key="item.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ item.product.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ item.quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${{ item.price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${{ item.subtotal }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">Total:</td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">${{ selectedOrder.total }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="mt-6 flex justify-end">
                    <button @click="showModal = false" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Close</button>
                </div>
            </div>
        </Modal>
    </div>
</template>
