<template>
  <div class="min-h-screen bg-gray-50">
    <Navbar />
    
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
          <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>

          <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
          <p class="text-gray-600 mb-8">Thank you for your purchase</p>

          <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <p class="text-sm text-gray-600 mb-2">Order ID</p>
            <p class="text-2xl font-bold text-gray-900">{{ route.params.id }}</p>
          </div>
        </div>

        <div v-if="loading" class="text-center py-8">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
        </div>

        <div v-else-if="order" class="border-t pt-6">
          <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
          
          <div class="mb-4">
            <p class="text-sm text-gray-600">Email</p>
            <p class="font-semibold">{{ order.customer_email }}</p>
          </div>

          <div class="space-y-3 mb-6">
            <h3 class="font-semibold text-gray-900">Items Ordered:</h3>
            <div v-for="item in order.items" :key="item.id" class="flex justify-between items-center py-3 border-b">
              <div class="flex-1">
                <p class="font-medium text-gray-900">{{ item.name }}</p>
                <p class="text-sm text-gray-600">Quantity: {{ item.quantity }} × ${{ item.price }}</p>
              </div>
              <p class="font-semibold text-gray-900">${{ (item.quantity * item.price).toFixed(2) }}</p>
            </div>
          </div>

          <div class="border-t pt-4 flex justify-between items-center">
            <span class="text-lg font-semibold">Total:</span>
            <span class="text-2xl font-bold text-blue-600">${{ order.total_amount }}</span>
          </div>
        </div>

        <div class="mt-8 text-center">
          <p class="text-gray-600 mb-6">
            A confirmation email has been sent to your email address with order details.
          </p>

          <router-link to="/" class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
            Continue Shopping
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import Navbar from '../components/Navbar.vue'

const route = useRoute()
const order = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  try {
    // Fetch order details from checkout service
    const response = await axios.get(`http://localhost:8002/api/orders/${route.params.id}`)
    order.value = response.data
  } catch (err) {
    console.error('Failed to fetch order:', err)
  } finally {
    loading.value = false
  }
})
</script>
