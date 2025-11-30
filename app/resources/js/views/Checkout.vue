<template>
  <div class="min-h-screen bg-gray-50">
    <Navbar />
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

      <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-md p-6">
          <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
          <div class="space-y-3">
            <div v-for="item in cartStore.items" :key="item.product_id" class="flex justify-between text-sm">
              <span>{{ item.name }} x{{ item.quantity }}</span>
              <span class="font-semibold">${{ (item.price * item.quantity).toFixed(2) }}</span>
            </div>
            <div class="border-t pt-3 flex justify-between font-bold text-lg">
              <span>Total:</span>
              <span class="text-blue-600">${{ cartStore.total.toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
          <h2 class="text-xl font-semibold mb-4">Customer Information</h2>
          <form @submit.prevent="handleCheckout" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input v-model="email" type="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            </div>

            <div v-if="error" class="p-3 bg-red-50 text-red-600 rounded-lg text-sm">
              {{ error }}
            </div>

            <button type="submit" :disabled="loading" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition disabled:opacity-50">
              {{ loading ? 'Processing...' : 'Place Order' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '../stores/cart'
import { useAuthStore } from '../stores/auth'
import Navbar from '../components/Navbar.vue'

const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()

const email = ref('')
const loading = ref(false)
const error = ref('')

onMounted(() => {
  if (authStore.user) {
    email.value = authStore.user.email
  }
})

async function handleCheckout() {
  loading.value = true
  error.value = ''
  
  try {
    const order = await cartStore.checkout(email.value)
    router.push(`/order/${order.id}`)
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Checkout failed'
  } finally {
    loading.value = false
  }
}
</script>
