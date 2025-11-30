<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <router-view />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuthStore } from './stores/auth'
import { useCartStore } from './stores/cart'

const authStore = useAuthStore()
const cartStore = useCartStore()

onMounted(async () => {
  // Check if user is authenticated on app load
  if (localStorage.getItem('token')) {
    await authStore.fetchUser()
  } else {
    // If not authenticated, load cart from localStorage
    await cartStore.initialize()
  }
})
</script>
