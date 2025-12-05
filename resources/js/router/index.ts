import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/auth/Login.vue';
import Register from '../pages/auth/Register.vue';
import Layout from '../layouts/Layout.vue';
import Dashboard from '../pages/Dashboard.vue';
import Products from '../pages/Products.vue';
import Orders from '../pages/Orders.vue';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/',
        component: Layout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                redirect: '/dashboard',
            },
            {
                path: 'dashboard',
                name: 'Dashboard',
                component: Dashboard,
            },
            {
                path: 'products',
                name: 'Products',
                component: Products,
            },
            {
                path: 'orders',
                name: 'Orders',
                component: Orders,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.meta.requiresAuth && !token) {
        next('/login');
    } else {
        next();
    }
});

export default router;
