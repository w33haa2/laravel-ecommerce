<template>
  <nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <router-link to="/" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            E-Shop
          </router-link>
        </div>

        <div class="flex items-center space-x-4">
          <router-link to="/cart" class="relative p-2 hover:bg-gray-100 rounded-full transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span v-if="cartStore.itemCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              {{ cartStore.itemCount }}
            </span>
          </router-link>

          <div v-if="authStore.isAuthenticated" class="flex items-center space-x-3">
            <span class="text-sm text-gray-700">{{ authStore.user?.name }}</span>
            <button @click="handleLogout" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
              Logout
            </button>
          </div>
          <div v-else class="flex items-center space-x-2">
            <router-link to="/login" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition">
              Login
            </router-link>
            <router-link to="/register" class="px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition">
              Register
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const cartStore = useCartStore()
const router = useRouter()

async function handleLogout() {
  await authStore.logout()
  router.push('/')
}
</script>
