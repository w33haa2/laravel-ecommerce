<template>
  <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden">
      <div class="text-6xl group-hover:scale-110 transition-transform duration-300">🛍️</div>
    </div>
    <div class="p-4">
      <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-2">{{ product.name }}</h3>
      <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ product.description }}</p>
      <div class="flex items-center justify-between">
        <span class="text-2xl font-bold text-blue-600">${{ product.price }}</span>
        <span class="text-sm text-gray-500">Stock: {{ product.stock }}</span>
      </div>
      <div class="mt-4 flex gap-2">
        <button @click="$emit('view')" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition">
          View
        </button>
        <button @click="$emit('add-to-cart')" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
          Add to Cart
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  product: any
}>()

defineEmits(['view', 'add-to-cart'])
</script>
