import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../lib/axios'
import { useCartStore } from './cart'

export const useAuthStore = defineStore('auth', () => {
    const user = ref<any>(null)
    const token = ref<string | null>(localStorage.getItem('token'))
    const loading = ref(false)
    const error = ref<string | null>(null)

    const isAuthenticated = computed(() => !!token.value && !!user.value)

    async function register(name: string, email: string, password: string, password_confirmation: string) {
        loading.value = true
        error.value = null
        try {
            const response = await api.post('/register', { name, email, password, password_confirmation })
            token.value = response.data.token
            user.value = response.data.user
            localStorage.setItem('token', response.data.token)
            return true
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Registration failed'
            return false
        } finally {
            loading.value = false
        }
    }

    async function login(email: string, password: string) {
        loading.value = true
        error.value = null
        try {
            const response = await api.post('/login', { email, password })
            token.value = response.data.token
            user.value = response.data.user
            localStorage.setItem('token', response.data.token)
            return true
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Login failed'
            return false
        } finally {
            loading.value = false
        }
    }

    async function logout() {
        try {
            await api.post('/logout')
        } catch (err) {
            console.error('Logout error:', err)
        } finally {
            user.value = null
            token.value = null
            localStorage.removeItem('token')
            // Clear cart on logout
            const cartStore = useCartStore()
            cartStore.clearCart()
        }
    }

    async function fetchUser() {
        if (!token.value) return

        try {
            const response = await api.get('/user')
            user.value = response.data
            // Load cart from server after fetching user
            const cartStore = useCartStore()
            await cartStore.loadFromServer()
        } catch (err) {
            console.error('Fetch user error:', err)
            user.value = null
            token.value = null
            localStorage.removeItem('token')
        }
    }

    return {
        user,
        token,
        loading,
        error,
        isAuthenticated,
        register,
        login,
        logout,
        fetchUser,
    }
})
