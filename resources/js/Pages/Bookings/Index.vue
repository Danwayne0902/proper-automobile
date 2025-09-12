<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Bookings
        </h2>
        <Link
          v-if="$page.props.auth.user.role === 'customer'"
          :href="route('bookings.create')"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          New Booking
        </Link>
      </div>
    </template>

    <!-- Search and Filters -->
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
              <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
              <input
                id="search"
                v-model="form.search"
                type="text"
                placeholder="Booking number, customer, car..."
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                @input="search"
              />
            </div>

            <!-- Status Filter -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
              <select
                id="status"
                v-model="form.status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                @change="search"
              >
                <option value="">All Statuses</option>
                <option v-for="status in statuses" :key="status" :value="status">
                  {{ status.charAt(0).toUpperCase() + status.slice(1) }}
                </option>
              </select>
            </div>

            <!-- Type Filter -->
            <div>
              <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
              <select
                id="type"
                v-model="form.type"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                @change="search"
              >
                <option value="">All Types</option>
                <option v-for="type in types" :key="type" :value="type">
                  {{ type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                </option>
              </select>
            </div>

            <!-- Reset Filters -->
            <div class="flex items-end">
              <button
                @click="resetFilters"
                class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
              >
                Reset Filters
              </button>
            </div>
          </div>
        </div>

        <!-- Bookings List -->
        <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
          <div v-if="bookings.data.length > 0" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Booking Details
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Automobile
                  </th>
                  <th v-if="$page.props.auth.user.role !== 'customer'" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Customer
                  </th>
                  <th v-if="$page.props.auth.user.role === 'customer'" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Dealer
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Scheduled Date
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="booking in bookings.data"
                  :key="booking.id"
                  class="hover:bg-gray-50"
                >
                  <!-- Booking Details -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900">{{ booking.booking_number }}</div>
                      <div class="text-gray-500">
                        {{ booking.type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                      </div>
                      <div v-if="booking.preferred_time" class="text-gray-500 text-xs">
                        Preferred: {{ booking.preferred_time }}
                      </div>
                    </div>
                  </td>

                  <!-- Automobile -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900">
                        {{ (booking.automobile && booking.automobile.year) ? booking.automobile.year : 'N/A' }}
                        {{ (booking.automobile && booking.automobile.make) ? booking.automobile.make : 'Unknown' }}
                        {{ (booking.automobile && booking.automobile.model) ? booking.automobile.model : 'Model' }}
                      </div>
                      <div class="text-gray-500">
                        ${{ (booking.automobile && booking.automobile.price) ? Number(booking.automobile.price).toLocaleString() : '0' }}
                      </div>
                    </div>
                  </td>

                  <!-- Customer (for dealers/admins) -->
                  <td v-if="$page.props.auth.user.role !== 'customer'" class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900">
                        {{ (booking.user && booking.user.name) ? booking.user.name : 'Unknown User' }}
                      </div>
                      <div class="text-gray-500">
                        {{ (booking.user && booking.user.email) ? booking.user.email : 'No email' }}
                      </div>
                    </div>
                  </td>

                  <!-- Dealer (for customers) -->
                  <td v-if="$page.props.auth.user.role === 'customer'" class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900">
                        {{ (booking.automobile && booking.automobile.dealer && booking.automobile.dealer.name) ? booking.automobile.dealer.name : 'Unknown Dealer' }}
                      </div>
                      <div class="text-gray-500">
                        {{ (booking.automobile && booking.automobile.dealer && booking.automobile.dealer.email) ? booking.automobile.dealer.email : 'No email' }}
                      </div>
                    </div>
                  </td>

                  <!-- Scheduled Date -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                      {{ formatDate(booking.scheduled_at) }}
                    </div>
                  </td>

                  <!-- Status -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="statusClass(booking.status)"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    >
                      {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
                    </span>
                  </td>

                  <!-- Actions -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                      <Link
                        :href="route('bookings.show', booking.id)"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        View
                      </Link>

                      <Link
                        v-if="canEdit(booking)"
                        :href="route('bookings.edit', booking.id)"
                        class="text-yellow-600 hover:text-yellow-900"
                      >
                        Edit
                      </Link>

                      <button
                        v-if="canDelete(booking)"
                        @click="deleteBooking(booking)"
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
              <path d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m-16-4c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
            <p class="mt-1 text-sm text-gray-500">
              {{ $page.props.auth.user.role === 'customer' ? 'Get started by booking a test drive.' : 'No bookings match your current filters.' }}
            </p>
            <div class="mt-6">
              <Link
                v-if="$page.props.auth.user.role === 'customer'"
                :href="route('bookings.create')"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                New Booking
              </Link>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="bookings.data.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing {{ bookings.from }} to {{ bookings.to }} of {{ bookings.total }} results
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <Link
                    v-if="bookings.prev_page_url"
                    :href="bookings.prev_page_url"
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                  >
                    Previous
                  </Link>
                  <Link
                    v-if="bookings.next_page_url"
                    :href="bookings.next_page_url"
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                  >
                    Next
                  </Link>
                </nav>
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
import { Inertia } from '@inertiajs/inertia'
import { debounce } from 'lodash'

export default {
  name: 'BookingsIndex',

  components: {
    AppLayout,
    Link
  },

  props: {
    bookings: {
      type: Object,
      required: true
    },
    filters: {
      type: [Object, Array],
      default: () => ({})
    },
    statuses: {
      type: Array,
      default: () => []
    },
    types: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      form: {
        search: this.filters.search || '',
        status: this.filters.status || '',
        type: this.filters.type || ''
      }
    }
  },

  methods: {
    search: debounce(function() {
      Inertia.get(route('bookings.index'), this.form, {
        preserveState: true,
        replace: true
      })
    }, 300),

    resetFilters() {
      this.form = {
        search: '',
        status: '',
        type: ''
      }
      this.search()
    },

    canEdit(booking) {
      const user = this.$page.props.auth.user
      return user.role === 'customer' &&
             booking.user_id === user.id &&
             booking.status === 'pending'
    },

    canDelete(booking) {
      const user = this.$page.props.auth.user
      return (user.role === 'admin') ||
             (user.role === 'customer' && booking.user_id === user.id &&
              ['pending', 'cancelled'].includes(booking.status)) ||
             (user.role === 'dealer' && booking.automobile.dealer_id === user.id &&
              ['pending', 'cancelled'].includes(booking.status))
    },

    deleteBooking(booking) {
      const bookingType = booking.type.replace('_', ' ')
      if (confirm(`Are you sure you want to delete this ${bookingType} booking (${booking.booking_number})?`)) {
        Inertia.delete(route('bookings.destroy', booking.id))
      }
    },

    formatDate(date) {
      if (!date) return 'N/A';
      const d = new Date(date);
      if (isNaN(d.getTime())) return 'Invalid Date';
      return d.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        weekday: 'short'
      })
    },

    statusClass(status) {
      const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-green-100 text-green-800',
        'completed': 'bg-blue-100 text-blue-800',
        'cancelled': 'bg-red-100 text-red-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
  }
}
</script>
