<template>
  <transition name="loader-fade">
    <div v-if="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-90 backdrop-blur-sm">
      <div class="text-center">
        <!-- Animated Car Icon -->
        <div class="relative mb-6">
          <img :src="logoUrl" alt="Proper Automobile" class="w-16 h-16 mx-auto animate-bounce object-contain">

          <!-- Animated wheels -->
          <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2">
            <div class="flex space-x-6">
              <div class="w-3 h-3 bg-gray-400 rounded-full animate-spin"></div>
              <div class="w-3 h-3 bg-gray-400 rounded-full animate-spin" style="animation-delay: 0.1s"></div>
            </div>
          </div>
        </div>

        <!-- Loading Text -->
        <div class="text-xl font-semibold text-gray-700 mb-2">
          {{ loadingText }}
        </div>

        <!-- Animated dots -->
        <div class="flex justify-center space-x-1">
          <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
          <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
          <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-6 w-64 mx-auto">
          <div class="bg-gray-200 rounded-full h-2">
            <div
              class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300 ease-out"
              :style="{ width: progress + '%' }"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'CustomLoader',
  props: {
    loading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      progress: 0,
      logoUrl: '/proper-autos.png',
      loadingTexts: [
        'Loading your perfect automobile...',
        'Preparing luxury vehicles...',
        'Starting your automotive journey...',
        'Getting everything ready...',
        'Loading premium content...'
      ],
      currentTextIndex: 0,
      progressInterval: null,
      textInterval: null
    }
  },
  computed: {
    loadingText() {
      return this.loadingTexts[this.currentTextIndex]
    }
  },
  watch: {
    loading(newVal) {
      if (newVal) {
        this.startLoading()
      } else {
        this.stopLoading()
      }
    }
  },
  methods: {
    startLoading() {
      this.progress = 0
      this.currentTextIndex = 0

      // Simulate progress
      this.progressInterval = setInterval(() => {
        if (this.progress < 90) {
          this.progress += Math.random() * 15
        }
      }, 200)

      // Rotate loading text
      this.textInterval = setInterval(() => {
        this.currentTextIndex = (this.currentTextIndex + 1) % this.loadingTexts.length
      }, 1500)
    },

    stopLoading() {
      this.progress = 100

      if (this.progressInterval) {
        clearInterval(this.progressInterval)
        this.progressInterval = null
      }

      if (this.textInterval) {
        clearInterval(this.textInterval)
        this.textInterval = null
      }

      // Complete the progress bar before hiding
      setTimeout(() => {
        this.progress = 0
      }, 300)
    }
  },

  beforeDestroy() {
    this.stopLoading()
  }
}
</script>

<style scoped>
.loader-fade-enter-active,
.loader-fade-leave-active {
  transition: opacity 0.3s ease;
}

.loader-fade-enter,
.loader-fade-leave-to {
  opacity: 0;
}

/* Custom animations */
@keyframes bounce {
  0%, 20%, 53%, 80%, 100% {
    transform: translate3d(0, 0, 0);
  }
  40%, 43% {
    transform: translate3d(0, -8px, 0);
  }
  70% {
    transform: translate3d(0, -4px, 0);
  }
  90% {
    transform: translate3d(0, -2px, 0);
  }
}

.animate-bounce {
  animation: bounce 1.5s infinite;
}

@keyframes spin-slow {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin-slow 1s linear infinite;
}

@keyframes pulse-slow {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.4;
  }
}

.animate-pulse {
  animation: pulse-slow 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
