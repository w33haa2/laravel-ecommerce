<template>
  <div class="min-h-screen bg-gray-50">
    <Navbar />
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

      <div v-if="cartStore.items.length === 0" class="text-center py-12 bg-white rounded-xl shadow-md">
        <p class="text-gray-600 mb-4">Your cart is empty</p>
        <router-link to="/" class="text-blue-600 hover:text-blue-700 font-semibold">Continue Shopping</router-link>
      </div>

      <div v-else class="space-y-4">
        <div v-for="item in cartStore.items" :key="item.product_id" class="bg-white rounded-xl shadow-md p-6 flex items-center gap-6">
          <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center text-4xl">
            🛍️
          </div>
          
          <div class="flex-1">
            <h3 class="font-semibold text-lg text-gray-900">{{ item.name }}</h3>
            <p class="text-gray-600">${{ item.price }}</p>
          </div>

          <div class="flex items-center gap-3">
            <button @click="cartStore.updateQuantity(item.product_id, item.quantity - 1)" :disabled="item.quantity <= 1" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50">-</button>
            <span class="w-12 text-center font-semibold">{{ item.quantity }}</span>
            <button @click="cartStore.updateQuantity(item.product_id, item.quantity + 1)" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300">+</button>
          </div>

          <div class="text-right">
            <p class="font-bold text-lg">${{ (item.price * item.quantity).toFixed(2) }}</p>
            <button @click="cartStore.removeItem(item.product_id)" class="text-red-600 hover:text-red-700 text-sm">Remove</button>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
          <div class="flex justify-between items-center mb-4">
            <span class="text-xl font-semibold">Total:</span>
            <span class="text-2xl font-bold text-blue-600">${{ cartStore.total.toFixed(2) }}</span>
          </div>
          <router-link to="/checkout" class="block w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold rounded-lg transition">
            Proceed to Checkout
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '../stores/cart'
import Navbar from '../components/Navbar.vue'

const cartStore = useCartStore()
</script>
