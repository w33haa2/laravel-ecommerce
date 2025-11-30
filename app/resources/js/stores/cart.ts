import { defineStore } from 'pinia'
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import api from '../lib/axios'
import { useAuthStore } from './auth'
import { API_CHECKOUT_URL } from '../config/api'

interface CartItem {
    id?: number
    product_id: number
    name: string
    price: number
    quantity: number
    image?: string
}

export const useCartStore = defineStore('cart', () => {
    const items = ref<CartItem[]>([])
    const loading = ref(false)

    const total = computed(() => {
        return items.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
    })

    const itemCount = computed(() => {
        return items.value.reduce((sum, item) => sum + item.quantity, 0)
    })

    // Load cart from localStorage on init
    function loadFromLocalStorage() {
        const stored = localStorage.getItem('cart')
        if (stored) {
            items.value = JSON.parse(stored)
        }
    }

    // Load cart from server (for authenticated users)
    async function loadFromServer() {
        const authStore = useAuthStore()
        // Check for token (user might not be loaded yet during initialization)
        if (!authStore.isAuthenticated && !localStorage.getItem('token')) {
            return
        }

        try {
            const response = await api.get('/cart')
            items.value = response.data || []
        } catch (err) {
            console.error('Load cart from server error:', err)
            // On 401, clear items (user is not authenticated)
            if ((err as any)?.response?.status === 401) {
                items.value = []
            } else {
                // On other errors, keep existing items or set to empty
                items.value = []
            }
        }
    }

    // Save cart to localStorage
    function saveToLocalStorage() {
        localStorage.setItem('cart', JSON.stringify(items.value))
    }

    // Watch for changes and save to localStorage
    watch(items, () => {
        const authStore = useAuthStore()
        if (!authStore.isAuthenticated) {
            saveToLocalStorage()
        }
    }, { deep: true })

    // Initialize cart based on auth state
    async function initialize() {
        const authStore = useAuthStore()
        // Check if token exists (user might be authenticated but user data not loaded yet)
        if (authStore.isAuthenticated || localStorage.getItem('token')) {
            // If authenticated or token exists, load from server
            await loadFromServer()
        } else {
            // If not authenticated, load from localStorage
            loadFromLocalStorage()
        }
    }

    async function syncWithServer() {
        const authStore = useAuthStore()
        if (!authStore.isAuthenticated) return

        try {
            // Get local cart items before fetching from server
            const localItems = [...items.value]

            // Fetch cart from server
            const response = await api.get('/cart')
            const serverItems = response.data

            // Start with server items
            const merged: CartItem[] = serverItems.map((item: CartItem) => ({ ...item }))

            // Merge local items into server items
            for (const localItem of localItems) {
                const existing = merged.find(i => i.product_id === localItem.product_id)
                if (existing) {
                    // If item exists on server, update quantity (merge quantities)
                    const newQuantity = existing.quantity + localItem.quantity
                    if (existing.id) {
                        // Update existing item on server
                        await api.put(`/cart/${existing.id}`, { quantity: newQuantity })
                        existing.quantity = newQuantity
                    }
                } else {
                    // If item doesn't exist on server, add it
                    const response = await api.post('/cart', {
                        product_id: localItem.product_id,
                        name: localItem.name,
                        price: localItem.price,
                        quantity: localItem.quantity,
                    })
                    merged.push(response.data)
                }
            }

            // Update local cart with merged items from server
            const updatedResponse = await api.get('/cart')
            items.value = updatedResponse.data

            // Clear localStorage since we're now using server cart
            localStorage.removeItem('cart')
        } catch (err) {
            console.error('Sync cart error:', err)
        }
    }

    async function addItem(product: any) {
        const authStore = useAuthStore()
        const existing = items.value.find(i => i.product_id === product.id)

        if (existing) {
            existing.quantity++
        } else {
            items.value.push({
                product_id: product.id,
                name: product.name,
                price: product.price,
                quantity: 1,
            })
        }

        if (authStore.isAuthenticated) {
            try {
                if (existing && existing.id) {
                    // Item exists on server, update quantity
                    const response = await api.put(`/cart/${existing.id}`, {
                        quantity: existing.quantity
                    })
                    // Update local item with server response to ensure sync
                    const index = items.value.findIndex(i => i.id === existing.id)
                    if (index !== -1) {
                        items.value[index] = response.data
                    }
                } else {
                    // Item might not exist on server, or we don't have its id yet
                    // POST will create or update (backend uses updateOrCreate which ADDS to existing)
                    // Always POST quantity 1 since we're adding one item
                    const response = await api.post('/cart', {
                        product_id: product.id,
                        name: product.name,
                        price: product.price,
                        quantity: 1, // Always 1 since backend adds to existing
                    })
                    // Update local item with server response (includes id and correct total quantity)
                    const index = items.value.findIndex(i => i.product_id === product.id && (!i.id || i.id === existing?.id))
                    if (index !== -1) {
                        // Replace with server response to ensure we have the correct data
                        items.value[index] = response.data
                    }
                }
            } catch (err) {
                console.error('Add to cart error:', err)
                // On error, reload cart from server to ensure sync
                if (authStore.isAuthenticated) {
                    await loadFromServer()
                }
            }
        }
    }

    async function updateQuantity(productId: number, quantity: number) {
        const item = items.value.find(i => i.product_id === productId)
        if (item) {
            item.quantity = quantity

            const authStore = useAuthStore()
            if (authStore.isAuthenticated && item.id) {
                try {
                    await api.put(`/cart/${item.id}`, { quantity })
                } catch (err) {
                    console.error('Update cart error:', err)
                }
            }
        }
    }

    async function removeItem(productId: number) {
        const index = items.value.findIndex(i => i.product_id === productId)
        if (index !== -1) {
            const item = items.value[index]
            items.value.splice(index, 1)

            const authStore = useAuthStore()
            if (authStore.isAuthenticated && item.id) {
                try {
                    await api.delete(`/cart/${item.id}`)
                } catch (err) {
                    console.error('Remove from cart error:', err)
                }
            }
        }
    }

    async function checkout(customerEmail: string) {
        loading.value = true
        try {
            const orderItems = items.value.map(item => ({
                product_id: item.product_id,
                name: item.name,
                quantity: item.quantity,
                price: item.price,
            }))

            const response = await axios.post(`${API_CHECKOUT_URL}/api/checkout`, {
                customer_email: customerEmail,
                items: orderItems,
            })

            // Clear cart after successful checkout
            const authStore = useAuthStore()
            if (authStore.isAuthenticated) {
                // Delete all cart items from server
                for (const item of items.value) {
                    if (item.id) {
                        try {
                            await api.delete(`/cart/${item.id}`)
                        } catch (err) {
                            console.error('Error deleting cart item:', err)
                        }
                    }
                }
            }

            items.value = []
            localStorage.removeItem('cart')

            return response.data
        } catch (err: any) {
            console.error('Checkout error:', err)
            throw err
        } finally {
            loading.value = false
        }
    }

    function clearCart() {
        items.value = []
        localStorage.removeItem('cart')
    }

    return {
        items,
        total,
        itemCount,
        loading,
        loadFromLocalStorage,
        loadFromServer,
        initialize,
        syncWithServer,
        addItem,
        updateQuantity,
        removeItem,
        checkout,
        clearCart,
    }
})
