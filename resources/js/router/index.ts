import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/auth/Login.vue';
import Register from '../pages/auth/Register.vue';
import Products from '../pages/Products.vue';

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
        path: '/products',
        name: 'Products',
        component: Products,
        meta: { requiresAuth: true },
    },
    {
        path: '/',
        redirect: '/login',
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
