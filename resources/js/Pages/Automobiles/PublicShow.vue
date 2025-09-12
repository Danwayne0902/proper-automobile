<template>
  <PublicLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ automobile.year }} {{ automobile.make }} {{ automobile.model }}
        </h2>
        <div class="flex space-x-2">
          <Link
            :href="route('automobiles.index')"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            Back to Inventory
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
            <!-- Images Section -->
            <div>
              <div class="space-y-4">
                <!-- Main Image -->
                <div class="relative h-96 bg-gray-200 rounded-lg overflow-hidden">
                  <img
                    v-if="selectedImage"
                    :src="`/storage/${selectedImage}`"
                    :alt="`${automobile.make} ${automobile.model}`"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="flex items-center justify-center h-full text-gray-500">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                    </svg>
                  </div>
                  <!-- Status Badge -->
                  <div class="absolute top-4 right-4">
                    <span
                      :class="statusClass(automobile.status)"
                      class="px-3 py-1 text-sm font-semibold rounded-full"
                    >
                      {{ automobile.status.charAt(0).toUpperCase() + automobile.status.slice(1) }}
                    </span>
                  </div>
                </div>

                <!-- Thumbnail Images -->
                <div v-if="automobile.images && automobile.images.length > 1" class="grid grid-cols-4 gap-2">
                  <button
                    v-for="(image, index) in automobile.images"
                    :key="index"
                    @click="selectedImage = image"
                    :class="[
                      'h-20 bg-gray-200 rounded-md overflow-hidden border-2 transition-all',
                      selectedImage === image ? 'border-blue-500' : 'border-transparent hover:border-gray-300'
                    ]"
                  >
                    <img
                      :src="`/storage/${image}`"
                      :alt="`${automobile.make} ${automobile.model} - Image ${index + 1}`"
                      class="w-full h-full object-cover"
                    />
                  </button>
                </div>
              </div>
            </div>

            <!-- Details Section -->
            <div class="space-y-6">
              <!-- Price and Basic Info -->
              <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                  {{ automobile.year }} {{ automobile.make }} {{ automobile.model }}
                </h1>
                <p class="text-4xl font-bold text-green-600 mb-4">
                  ${{ Number(automobile.price).toLocaleString() }}
                </p>
                <div class="mb-4">
                  <span
                    :class="statusClass(automobile.status)"
                    class="px-3 py-1 text-sm font-semibold rounded-full"
                  >
                    {{ automobile.status.charAt(0).toUpperCase() + automobile.status.slice(1) }}
                  </span>
                </div>
                <p class="text-gray-600 text-sm mb-4">VIN: {{ automobile.vin }}</p>
              </div>

              <!-- Specifications -->
              <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Year:</span>
                    <span class="text-gray-900">{{ automobile.year }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Make:</span>
                    <span class="text-gray-900">{{ automobile.make }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Model:</span>
                    <span class="text-gray-900">{{ automobile.model }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Mileage:</span>
                    <span class="text-gray-900">{{ Number(automobile.mileage).toLocaleString() }} miles</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Color:</span>
                    <span class="text-gray-900">{{ automobile.color }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Body Type:</span>
                    <span class="text-gray-900">{{ automobile.body_type }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Fuel Type:</span>
                    <span class="text-gray-900">{{ automobile.fuel_type }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Transmission:</span>
                    <span class="text-gray-900">{{ automobile.transmission }}</span>
                  </div>
                  <div v-if="automobile.engine_size" class="flex justify-between col-span-2">
                    <span class="font-medium text-gray-700">Engine Size:</span>
                    <span class="text-gray-900">{{ automobile.engine_size }}</span>
                  </div>
                </div>
              </div>

              <!-- Description -->
              <div v-if="automobile.description">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                <p class="text-gray-700 leading-relaxed">{{ automobile.description }}</p>
              </div>

              <!-- Dealer Information -->
              <div v-if="automobile.dealer" class="bg-blue-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Dealer Information</h3>
                <div class="space-y-2 text-sm">
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Name:</span>
                    <span class="text-gray-900">{{ automobile.dealer.name }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Email:</span>
                    <span class="text-gray-900">{{ automobile.dealer.email }}</span>
                  </div>
                  <div v-if="automobile.dealer.phone" class="flex justify-between">
                    <span class="font-medium text-gray-700">Phone:</span>
                    <span class="text-gray-900">{{ automobile.dealer.phone }}</span>
                  </div>
                </div>
              </div>

              <!-- Action Buttons for Unauthenticated Users -->
              <div class="space-y-3">
                <div v-if="automobile.status === 'sold'" class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                  <h3 class="text-lg font-semibold text-red-800">This vehicle has been sold</h3>
                  <p class="text-red-600 mt-1">Please check our inventory for other available vehicles.</p>
                </div>

                <Link
                  v-if="automobile.status === 'available'"
                  :href="route('register')"
                  class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors text-center block"
                >
                  Register to Book Test Drive
                </Link>

                <Link
                  v-if="automobile.status === 'available'"
                  :href="route('register')"
                  class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors text-center block"
                >
                  Register to Make an Offer
                </Link>

                <Link
                  :href="route('automobiles.index')"
                  class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg transition-colors text-center block"
                >
                  Browse All Vehicles
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import PublicLayout from '@/Components/Layout/PublicLayout.vue'

export default {
  name: 'AutomobilesPublicShow',

  components: {
    PublicLayout,
    Link
  },

  props: {
    automobile: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      selectedImage: null
    }
  },

  mounted() {
    // Set the first image as selected by default
    if (this.automobile.images && this.automobile.images.length > 0) {
      this.selectedImage = this.automobile.images[0]
    }
  },

  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },

    statusClass(status) {
      const classes = {
        'available': 'bg-green-100 text-green-800',
        'sold': 'bg-red-100 text-red-800',
        'reserved': 'bg-yellow-100 text-yellow-800',
        'maintenance': 'bg-gray-100 text-gray-800',
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-green-100 text-green-800',
        'completed': 'bg-blue-100 text-blue-800',
        'cancelled': 'bg-red-100 text-red-800',
        'failed': 'bg-red-100 text-red-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
  }
}
</script>
