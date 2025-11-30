import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useProductsStore = defineStore('products', () => {
    const products = ref<any[]>([])
    const currentProduct = ref<any>(null)
    const loading = ref(false)
    const error = ref<string | null>(null)

    async function fetchProducts() {
        loading.value = true
        error.value = null
        try {
            const response = await axios.get('http://localhost:8001/api/products')
            products.value = response.data.data || response.data
        } catch (err: any) {
            error.value = err.message || 'Failed to fetch products'
            console.error('Fetch products error:', err)
        } finally {
            loading.value = false
        }
    }

    async function fetchProduct(id: string | number) {
        loading.value = true
        error.value = null
        try {
            const response = await axios.get(`http://localhost:8001/api/products/${id}`)
            currentProduct.value = response.data
            return response.data
        } catch (err: any) {
            error.value = err.message || 'Failed to fetch product'
            console.error('Fetch product error:', err)
            return null
        } finally {
            loading.value = false
        }
    }

    return {
        products,
        currentProduct,
        loading,
        error,
        fetchProducts,
        fetchProduct,
    }
})
