<template>
  <AppLayout title="Profile Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Profile Dashboard
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Profile Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center">
                  <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">{{ user.name }}</h3>
                <p class="text-sm text-gray-500">{{ user.email }}</p>
                <p class="text-sm text-gray-500 capitalize">{{ user.role }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats and Recent Activity -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <!-- Bookings Count -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ bookingsCount }}</h3>
                  <p class="text-sm text-gray-500">Total Bookings</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Transactions Count -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ transactionsCount }}</h3>
                  <p class="text-sm text-gray-500">Transactions</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Pending Items -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                  <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ pendingCount }}</h3>
                  <p class="text-sm text-gray-500">Pending Items</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Bookings</h3>
            <div v-if="recentBookings.length > 0">
              <div v-for="booking in recentBookings" :key="booking.id" class="border-b border-gray-200 py-3 last:border-0">
                <div class="flex justify-between items-center">
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ booking.booking_number }}</p>
                    <p class="text-sm text-gray-500">{{ formatDate(booking.scheduled_at) }} - {{ booking.type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}</p>
                  </div>
                  <span :class="statusClass(booking.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                    {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
                  </span>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-4">
              <p class="text-gray-500">No bookings yet.</p>
            </div>
          </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Transactions</h3>
            <div v-if="recentTransactions.length > 0">
              <div v-for="transaction in recentTransactions" :key="transaction.id" class="border-b border-gray-200 py-3 last:border-0">
                <div class="flex justify-between items-center">
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ transaction.transaction_number }}</p>
                    <p class="text-sm text-gray-500">{{ formatCurrency(transaction.amount) }} - {{ transaction.type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}</p>
                  </div>
                  <span :class="statusClass(transaction.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                    {{ transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1) }}
                  </span>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-4">
              <p class="text-gray-500">No transactions yet.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from '@/Components/Layout/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue'

export default {
  name: 'ProfileDashboard',

  components: {
    AppLayout,
    Link
  },

  props: {
    user: {
      type: Object,
      required: true
    },
    bookingsCount: {
      type: Number,
      default: 0
    },
    transactionsCount: {
      type: Number,
      default: 0
    },
    pendingCount: {
      type: Number,
      default: 0
    },
    recentBookings: {
      type: Array,
      default: () => []
    },
    recentTransactions: {
      type: Array,
      default: () => []
    }
  },

  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    },

    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount)
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
    }
  }
}
</script>
