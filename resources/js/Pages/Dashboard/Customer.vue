<template>
  <AppLayout title="Customer Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Customer Dashboard
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Message for New Customers -->
        <div v-if="isNewCustomer" class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-lg font-medium text-blue-800">Welcome to Proper Automobile!</h3>
              <div class="mt-2 text-blue-700">
                <p class="mb-2">Thank you for joining us. As a new customer, here are some things you can do:</p>
                <ul class="list-disc list-inside space-y-1">
                  <li>Browse our complete inventory of luxury vehicles</li>
                  <li>Schedule a test drive for any vehicle that interests you</li>
                  <li>Make offers on vehicles you're interested in purchasing</li>
                  <li>View and manage your bookings and transactions</li>
                </ul>
              </div>
              <div class="mt-4">
                <Link :href="route('automobiles.index')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                  Browse Our Inventory
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ stats.my_bookings }}</h3>
                  <p class="text-sm text-gray-500">My Bookings</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ stats.pending_bookings }}</h3>
                  <p class="text-sm text-gray-500">Pending</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ stats.confirmed_bookings }}</h3>
                  <p class="text-sm text-gray-500">Confirmed</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ formatCurrency(stats.total_spent) }}</h3>
                  <p class="text-sm text-gray-500">Total Spent</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <!-- Recent Bookings -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">My Recent Bookings</h3>
              <div class="space-y-4">
                <div v-for="booking in recentBookings" :key="booking.id" class="flex items-center justify-between border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ booking.booking_number }}</p>
                    <p class="text-xs text-gray-500">{{ booking.automobile.year }} {{ booking.automobile.make }} {{ booking.automobile.model }} • {{ formatDate(booking.scheduled_at) }}</p>
                  </div>
                  <div class="text-right">
                    <span :class="statusClass(booking.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                      {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
                    </span>
                  </div>
                </div>
                <div v-if="recentBookings.length === 0" class="text-center py-4 text-gray-500">
                  No bookings yet. <Link :href="route('automobiles.index')" class="text-indigo-600 hover:text-indigo-900">Browse our inventory</Link> to schedule a test drive.
                </div>
                <div v-else class="pt-2">
                  <Link :href="route('bookings.index')" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                    View all bookings →
                  </Link>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Transactions -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">My Recent Transactions</h3>
              <div class="space-y-4">
                <div v-for="transaction in recentTransactions" :key="transaction.id" class="flex items-center justify-between border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ transaction.transaction_number }}</p>
                    <p class="text-xs text-gray-500">{{ transaction.automobile.year }} {{ transaction.automobile.make }} {{ transaction.automobile.model }} • {{ formatDate(transaction.created_at) }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ formatCurrency(transaction.amount) }}</p>
                    <span :class="statusClass(transaction.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                      {{ transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1) }}
                    </span>
                  </div>
                </div>
                <div v-if="recentTransactions.length === 0" class="text-center py-4 text-gray-500">
                  No transactions yet. <Link :href="route('automobiles.index')" class="text-indigo-600 hover:text-indigo-900">Browse our inventory</Link> to find your dream car.
                </div>
                <div v-else class="pt-2">
                  <Link :href="route('transactions.index')" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                    View all transactions →
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recommended Automobiles -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ isNewCustomer ? 'Featured Vehicles' : 'Recommended for You' }}</h3>
            <div v-if="recommendedAutomobiles.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="automobile in recommendedAutomobiles" :key="automobile.id" class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="h-48 bg-gray-200 relative">
                  <img v-if="automobile.images && automobile.images.length > 0" :src="`/storage/${automobile.images[0]}`" :alt="`${automobile.year} ${automobile.make} ${automobile.model}`" class="w-full h-full object-cover" @error="handleImageError">
                  <div v-else class="w-full h-full flex items-center justify-center bg-gray-100">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">
                    {{ automobile.condition }}
                  </div>
                </div>
                <div class="p-4">
                  <h4 class="text-lg font-semibold text-gray-900">{{ automobile.year }} {{ automobile.make }} {{ automobile.model }}</h4>
                  <p class="text-gray-600 text-sm mt-1" v-if="automobile.dealer && automobile.dealer.name">{{ automobile.dealer.name }}</p>
                  <p class="text-gray-600 text-sm mt-1" v-else>Dealer information not available</p>
                  <div class="mt-2 flex justify-between items-center">
                    <span class="text-lg font-bold text-green-600">{{ formatCurrency(automobile.price) }}</span>
                    <Link :href="route('automobiles.show', automobile.id)" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                      View Details
                    </Link>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-4 text-gray-500">
              No recommendations available
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import AppLayout from '@/Components/Layout/AppLayout.vue'

export default {
  name: 'CustomerDashboard',

  components: {
    AppLayout,
    Link
  },

  props: {
    stats: {
      type: Object,
      required: true
    },
    recentBookings: {
      type: Array,
      default: () => []
    },
    recentTransactions: {
      type: Array,
      default: () => []
    },
    recommendedAutomobiles: {
      type: Array,
      default: () => []
    },
    isNewCustomer: {
      type: Boolean,
      default: false
    }
  },

  methods: {
    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount || 0)
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric'
      })
    },

    statusClass(status) {
      const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-green-100 text-green-800',
        'completed': 'bg-blue-100 text-blue-800',
        'cancelled': 'bg-red-100 text-red-800',
        'processing': 'bg-blue-100 text-blue-800',
        'failed': 'bg-red-100 text-red-800',
        'refunded': 'bg-gray-100 text-gray-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    },

    handleImageError(event) {
      // Fallback to a placeholder image if the car image fails to load
      event.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjI1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZTVlN2ViIi8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzZiNzI4MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkNhciBJbWFnZTwvdGV4dD4KICA8cGF0aCBkPSJtMTAwIDEwMCA2MCAzMGMxMCAzIDIwIDMgMzAgMGw2MC0zMGMxMC0zIDEwLTcgMC0xMGwtNjAtMzBjLTEwLTMtMjAtMy0zMCAwbC02MCAzMGMtMTAgMyAtMTA3NyAwIDEweiIgZmlsbD0iIzliOWI5YiIvPgo8L3N2Zz4K'
    }
  }
}
</script>
