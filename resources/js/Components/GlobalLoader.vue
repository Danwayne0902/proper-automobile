<template>
  <div v-show="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-sm mx-4 text-center">
      <!-- Animated Logo -->
      <div class="mb-4">
        <img :src="logoUrl" alt="Proper Automobile" class="h-16 w-16 mx-auto animate-bounce">
      </div>

      <!-- Company Name -->
      <h3 class="text-xl font-bold text-gray-900 mb-2">
        <span class="text-blue-600">Proper</span> Automobile
      </h3>

      <!-- Loading Animation -->
      <div class="flex justify-center mb-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>

      <!-- Loading Text -->
      <p class="text-gray-600 text-sm">Loading your experience...</p>

      <!-- Progress Bar Animation -->
      <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
        <div class="bg-gradient-to-r from-blue-600 to-blue-400 h-2 rounded-full animate-pulse" style="width: 60%"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'GlobalLoader',
  props: {
    loading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      logoUrl: '/proper-autos.png'
    }
  }
}
</script>

<style scoped>
/* Custom animations */
@keyframes bounce {
  0%, 20%, 53%, 80%, 100% {
    transform: translateY(0);
  }
  40%, 43% {
    transform: translateY(-10px);
  }
  70% {
    transform: translateY(-5px);
  }
  90% {
    transform: translateY(-2px);
  }
}

.animate-bounce {
  animation: bounce 2s infinite;
}
</style>
