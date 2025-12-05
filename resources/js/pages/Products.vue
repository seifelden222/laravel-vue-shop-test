<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Modal from '../components/Modal.vue';

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    stock: number;
    status: string;
}

const products = ref<Product[]>([]);
const showModal = ref(false);
const isEditing = ref(false);
const form = ref({
    id: null as number | null,
    name: '',
    description: '',
    price: 0,
    stock: 0,
});

const fetchProducts = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/products', {
            headers: { Authorization: `Bearer ${token}` },
        });
        products.value = response.data.products;
    } catch (error) {
        console.error('Error fetching products:', error);
    }
};

const openCreateModal = () => {
    isEditing.value = false;
    form.value = { id: null, name: '', description: '', price: 0, stock: 0 };
    showModal.value = true;
};

const openEditModal = (product: Product) => {
    isEditing.value = true;
    form.value = { ...product };
    showModal.value = true;
};

const saveProduct = async () => {
    const token = localStorage.getItem('token');
    try {
        if (isEditing.value && form.value.id) {
            await axios.put(`/api/products/${form.value.id}`, form.value, {
                headers: { Authorization: `Bearer ${token}` },
            });
        } else {
            await axios.post('/api/products', form.value, {
                headers: { Authorization: `Bearer ${token}` },
            });
        }
        showModal.value = false;
        fetchProducts();
    } catch (error) {
        console.error('Error saving product:', error);
    }
};

const deleteProduct = async (id: number) => {
    if (!confirm('Are you sure you want to delete this product?')) return;
    try {
        const token = localStorage.getItem('token');
        await axios.delete(`/api/products/${id}`, {
            headers: { Authorization: `Bearer ${token}` },
        });
        fetchProducts();
    } catch (error) {
        console.error('Error deleting product:', error);
    }
};

onMounted(() => {
    fetchProducts();
});
</script>

<template>
    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Products Management</h2>
            <button @click="openCreateModal" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Add Product
            </button>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="product in products" :key="product.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ product.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${{ product.price }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ product.stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="product.status === 'in_stock' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ product.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openEditModal(product)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                            <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <Modal :show="showModal" :title="isEditing ? 'Edit Product' : 'Add Product'" @close="showModal = false">
            <form @submit.prevent="saveProduct" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input v-model="form.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea v-model="form.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 border"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                    <input v-model="form.price" type="number" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                    <input v-model="form.stock" type="number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2 border">
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showModal = false" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </Modal>
    </div>
</template>
