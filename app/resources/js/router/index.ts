import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/Home.vue'),
        },
        {
            path: '/products/:id',
            name: 'product-detail',
            component: () => import('../views/ProductDetail.vue'),
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/Login.vue'),
            meta: { guest: true },
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('../views/Register.vue'),
            meta: { guest: true },
        },
        {
            path: '/cart',
            name: 'cart',
            component: () => import('../views/Cart.vue'),
        },
        {
            path: '/checkout',
            name: 'checkout',
            component: () => import('../views/Checkout.vue'),
        },
        {
            path: '/order/:id',
            name: 'order-confirmation',
            component: () => import('../views/OrderConfirmation.vue'),
        },
    ],
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    if (to.meta.guest && authStore.isAuthenticated) {
        next({ name: 'home' })
    } else {
        next()
    }
})

export default router
