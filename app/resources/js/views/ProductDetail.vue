<template>
  <div class="min-h-screen bg-gray-50">
    <Navbar />
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <button @click="router.back()" class="mb-6 text-blue-600 hover:text-blue-700 flex items-center gap-2">
        ← Back to Products
      </button>

      <div v-if="productsStore.loading" class="animate-pulse">
        <div class="bg-white rounded-xl shadow-lg p-8 h-96"></div>
      </div>

      <div v-else-if="product" class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="grid md:grid-cols-2 gap-8 p-8">
          <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
            <div class="text-9xl">🛍️</div>
          </div>

          <div class="flex flex-col justify-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
            <p class="text-gray-600 text-lg mb-6">{{ product.description }}</p>
            
            <div class="mb-6">
              <span class="text-4xl font-bold text-blue-600">${{ product.price }}</span>
              <p class="text-gray-500 mt-2">Stock: {{ product.stock }} available</p>
            </div>

            <button 
              @click="handleAddToCart"
              class="w-full md:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-lg transition"
            >
              Add to Cart
            </button>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-12">
        <p class="text-gray-600">Product not found</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProductsStore } from '../stores/products'
import { useCartStore } from '../stores/cart'
import Navbar from '../components/Navbar.vue'

const route = useRoute()
const router = useRouter()
const productsStore = useProductsStore()
const cartStore = useCartStore()
const product = ref<any>(null)

onMounted(async () => {
  product.value = await productsStore.fetchProduct(route.params.id as string)
})

function handleAddToCart() {
  if (product.value) {
    cartStore.addItem(product.value)
    router.push('/cart')
  }
}
</script>
