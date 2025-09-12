<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          User Profile
        </h2>
        <Link
          :href="route('admin.users.index')"
          class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
        >
          Back to Users
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- User Profile Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-6">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
              <!-- Avatar -->
              <div class="flex-shrink-0">
                <div class="h-32 w-32 rounded-full bg-gray-300 flex items-center justify-center">
                  <span class="text-4xl font-bold text-gray-700">
                    {{ user.name.charAt(0).toUpperCase() }}
                  </span>
                </div>
              </div>

              <!-- User Details -->
              <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                  <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ user.name }}</h1>
                    <div class="mt-1">
                      <span
                        :class="getRoleBadgeClass(user.role)"
                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full"
                      >
                        {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                      </span>
                    </div>
                  </div>
                  <div class="mt-4 md:mt-0">
                    <Link
                      :href="route('admin.users.edit', user.id)"
                      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                      Edit User
                    </Link>
                  </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                    <dl class="mt-2 space-y-3">
                      <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ user.email }}</dd>
                      </div>
                      <div v-if="user.phone">
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ user.phone }}</dd>
                      </div>
                      <div v-if="user.address">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ user.address }}</dd>
                      </div>
                      <div>
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(user.created_at) }}</dd>
                      </div>
                      <div v-if="user.email_verified_at">
                        <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(user.email_verified_at) }}</dd>
                      </div>
                    </dl>
                  </div>

                  <div>
                    <h3 class="text-lg font-medium text-gray-900">Activity Summary</h3>
                    <dl class="mt-2 space-y-3">
                      <div>
                        <dt class="text-sm font-medium text-gray-500">Automobiles</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ stats.automobiles_count }}</dd>
                      </div>
                      <div>
                        <dt class="text-sm font-medium text-gray-500">Bookings</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ stats.bookings_count }}</dd>
                      </div>
                      <div>
                        <dt class="text-sm font-medium text-gray-500">Transactions</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ stats.transactions_count }}</dd>
                      </div>
                      <div v-if="user.role === 'customer'">
                        <dt class="text-sm font-medium text-gray-500">Total Spent</dt>
                        <dd class="mt-1 text-sm text-gray-900">${{ Number(stats.total_spent).toLocaleString() }}</dd>
                      </div>
                      <div v-if="user.role === 'dealer'">
                        <dt class="text-sm font-medium text-gray-500">Total Earned</dt>
                        <dd class="mt-1 text-sm text-gray-900">${{ Number(stats.total_earned).toLocaleString() }}</dd>
                      </div>
                    </dl>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Activity Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Automobiles -->
          <div v-if="user.automobiles && user.automobiles.length > 0" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Automobiles ({{ user.automobiles.length }})
              </h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div
                  v-for="automobile in user.automobiles"
                  :key="automobile.id"
                  class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50"
                >
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-md flex items-center justify-center">
                      <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <h4 class="text-sm font-medium text-gray-900">
                        {{ automobile.year }} {{ automobile.make }} {{ automobile.model }}
                      </h4>
                      <p class="text-sm text-gray-500">${{ Number(automobile.price).toLocaleString() }}</p>
                      <span
                        :class="statusClass(automobile.status)"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      >
                        {{ automobile.status.charAt(0).toUpperCase() + automobile.status.slice(1) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Bookings -->
          <div v-if="user.bookings && user.bookings.length > 0" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Recent Bookings ({{ user.bookings.length }})
              </h3>
            </div>
            <div class="p-6">
              <div class="flow-root">
                <ul class="divide-y divide-gray-200">
                  <li
                    v-for="booking in user.bookings"
                    :key="booking.id"
                    class="py-4"
                  >
                    <div class="flex items-center">
                      <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                          <svg v-if="booking.type === 'test_drive'" class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                          <svg v-else class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </div>
                      </div>
                      <div class="ml-4 min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">
                          {{ booking.automobile.year }} {{ booking.automobile.make }} {{ booking.automobile.model }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                          {{ booking.type === 'test_drive' ? 'Test Drive' : 'Reservation' }} - {{ formatDate(booking.scheduled_at) }}
                        </p>
                        <span
                          :class="bookingStatusClass(booking.status)"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        >
                          {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
                        </span>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Recent Transactions -->
          <div v-if="user.transactions && user.transactions.length > 0" class="bg-white overflow-hidden shadow-xl sm:rounded-lg lg:col-span-2">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Recent Transactions ({{ user.transactions.length }})
              </h3>
            </div>
            <div class="p-6">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Automobile
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Amount
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="transaction in user.transactions" :key="transaction.id">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ transaction.automobile.year }} {{ transaction.automobile.make }} {{ transaction.automobile.model }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ transaction.type }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${{ Number(transaction.amount).toLocaleString() }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span
                          :class="transactionStatusClass(transaction.status)"
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        >
                          {{ transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1) }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatDate(transaction.created_at) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
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
  name: 'AdminUsersShow',
  components: {
    AppLayout,
    Link
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    stats: {
      type: Object,
      required: true
    }
  },
  methods: {
    getRoleBadgeClass(role) {
      switch (role) {
        case 'admin':
          return 'bg-red-100 text-red-800'
        case 'dealer':
          return 'bg-blue-100 text-blue-800'
        case 'customer':
          return 'bg-green-100 text-green-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    },
    statusClass(status) {
      const classes = {
        'available': 'bg-green-100 text-green-800',
        'sold': 'bg-red-100 text-red-800',
        'reserved': 'bg-yellow-100 text-yellow-800',
        'maintenance': 'bg-gray-100 text-gray-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    },
    bookingStatusClass(status) {
      const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'approved': 'bg-blue-100 text-blue-800',
        'completed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    },
    transactionStatusClass(status) {
      const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'completed': 'bg-green-100 text-green-800',
        'failed': 'bg-red-100 text-red-800',
        'refunded': 'bg-gray-100 text-gray-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
  }
}
</script>
