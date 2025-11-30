<template>
  <div class="min-h-screen bg-gray-50">
    <Navbar />
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Product Catalog</h1>
        <p class="text-gray-600">Discover our amazing products</p>
      </div>

      <div v-if="productsStore.loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="i in 8" :key="i" class="bg-white rounded-xl shadow-md h-96 animate-pulse"></div>
      </div>

      <div v-else-if="productsStore.error" class="text-center py-12">
        <p class="text-red-600">{{ productsStore.error }}</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <ProductCard
          v-for="product in productsStore.products"
          :key="product.id"
          :product="product"
          @view="router.push(`/products/${product.id}`)"
          @add-to-cart="handleAddToCart(product)"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useProductsStore } from '../stores/products'
import { useCartStore } from '../stores/cart'
import Navbar from '../components/Navbar.vue'
import ProductCard from '../components/ProductCard.vue'

const router = useRouter()
const productsStore = useProductsStore()
const cartStore = useCartStore()

onMounted(() => {
  productsStore.fetchProducts()
  cartStore.loadFromLocalStorage()
})

function handleAddToCart(product: any) {
  cartStore.addItem(product)
}
</script>
