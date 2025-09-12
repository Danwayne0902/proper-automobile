<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Transaction #{{ transaction.transaction_number }}
        </h2>
        <div class="flex space-x-2">
          <Link
            v-if="transaction.status === 'completed'"
            :href="route('transactions.invoice', transaction.id)"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
          >
            View Invoice
          </Link>
          <Link
            :href="route('transactions.index')"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            Back to List
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          <!-- Main Transaction Details -->
          <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <div class="space-y-6">

                <!-- Transaction Header -->
                <div class="border-b border-gray-200 pb-4">
                  <div class="flex justify-between items-start">
                    <div>
                      <h3 class="text-lg font-medium text-gray-900">{{ transaction.transaction_number }}</h3>
                      <p class="text-sm text-gray-600">
                        {{ transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1) }} Transaction
                      </p>
                    </div>
                    <span
                      :class="statusClass(transaction.status)"
                      class="px-3 py-1 text-sm font-semibold rounded-full"
                    >
                      {{ transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1) }}
                    </span>
                  </div>
                </div>

                <!-- Amount and Payment Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <h4 class="text-md font-medium text-gray-900 mb-2">Payment Information</h4>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                      <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Amount:</span>
                        <span class="text-lg font-bold text-green-600">
                          ${{ Number(transaction.amount).toLocaleString() }}
                        </span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Payment Method:</span>
                        <span class="text-sm text-gray-900">{{ formatPaymentMethod(transaction.payment_method) }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Created:</span>
                        <span class="text-sm text-gray-900">{{ formatDate(transaction.created_at) }}</span>
                      </div>
                      <div v-if="transaction.processed_at" class="flex justify-between">
                        <span class="text-sm text-gray-600">Processed:</span>
                        <span class="text-sm text-gray-900">{{ formatDate(transaction.processed_at) }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- Status Management -->
                  <div v-if="canManage">
                    <h4 class="text-md font-medium text-gray-900 mb-2">Status Management</h4>
                    <form @submit.prevent="updateStatus" class="bg-gray-50 rounded-lg p-4 space-y-4">
                      <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select
                          id="status"
                          v-model="statusForm.status"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                          <option value="pending">Pending</option>
                          <option value="processing">Processing</option>
                          <option value="completed">Completed</option>
                          <option value="failed">Failed</option>
                          <option value="refunded">Refunded</option>
                        </select>
                      </div>
                      <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea
                          id="notes"
                          v-model="statusForm.notes"
                          rows="2"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Status update notes..."
                        ></textarea>
                      </div>
                      <button
                        type="submit"
                        :disabled="statusProcessing || statusForm.status === transaction.status"
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                      >
                        {{ statusProcessing ? 'Updating...' : 'Update Status' }}
                      </button>
                    </form>
                  </div>
                </div>

                <!-- Automobile Information -->
                <div>
                  <h4 class="text-md font-medium text-gray-900 mb-3">Automobile Details</h4>
                  <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start space-x-4">
                      <div class="flex-1">
                        <h5 class="font-medium text-gray-900">
                          {{ transaction.automobile.year }} {{ transaction.automobile.make }} {{ transaction.automobile.model }}
                        </h5>
                        <div class="mt-2 grid grid-cols-2 gap-4 text-sm text-gray-600">
                          <div>VIN: {{ transaction.automobile.vin }}</div>
                          <div>Color: {{ transaction.automobile.color }}</div>
                          <div>Mileage: {{ Number(transaction.automobile.mileage).toLocaleString() }} miles</div>
                          <div>Price: ${{ Number(transaction.automobile.price).toLocaleString() }}</div>
                        </div>
                      </div>
                      <Link
                        :href="route('automobiles.show', transaction.automobile.id)"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                      >
                        View Details
                      </Link>
                    </div>
                  </div>
                </div>

                <!-- Related Booking -->
                <div v-if="transaction.booking">
                  <h4 class="text-md font-medium text-gray-900 mb-3">Related Booking</h4>
                  <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                      <div>
                        <h5 class="font-medium text-gray-900">{{ transaction.booking.booking_number }}</h5>
                        <p class="text-sm text-gray-600">
                          {{ transaction.booking.type.charAt(0).toUpperCase() + transaction.booking.type.slice(1) }} -
                          {{ formatDate(transaction.booking.scheduled_at) }}
                        </p>
                      </div>
                      <Link
                        :href="route('bookings.show', transaction.booking.id)"
                        class="text-green-600 hover:text-green-800 text-sm font-medium"
                      >
                        View Booking
                      </Link>
                    </div>
                  </div>
                </div>

                <!-- Description -->
                <div v-if="transaction.description">
                  <h4 class="text-md font-medium text-gray-900 mb-2">Description</h4>
                  <p class="text-gray-700 bg-gray-50 rounded-lg p-4">{{ transaction.description }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-6">

            <!-- Customer Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-3">Customer Information</h4>
              <div class="space-y-3">
                <div>
                  <span class="text-sm font-medium text-gray-700">Name:</span>
                  <p class="text-sm text-gray-900">{{ transaction.user.name }}</p>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-700">Email:</span>
                  <p class="text-sm text-gray-900">{{ transaction.user.email }}</p>
                </div>
                <div v-if="transaction.user.phone">
                  <span class="text-sm font-medium text-gray-700">Phone:</span>
                  <p class="text-sm text-gray-900">{{ transaction.user.phone }}</p>
                </div>
              </div>
            </div>

            <!-- Dealer Information -->
            <div v-if="transaction.automobile.dealer" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-3">Dealer Information</h4>
              <div class="space-y-3">
                <div>
                  <span class="text-sm font-medium text-gray-700">Name:</span>
                  <p class="text-sm text-gray-900">{{ transaction.automobile.dealer.name }}</p>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-700">Email:</span>
                  <p class="text-sm text-gray-900">{{ transaction.automobile.dealer.email }}</p>
                </div>
                <div v-if="transaction.automobile.dealer.phone">
                  <span class="text-sm font-medium text-gray-700">Phone:</span>
                  <p class="text-sm text-gray-900">{{ transaction.automobile.dealer.phone }}</p>
                </div>
              </div>
            </div>

            <!-- Transaction Actions -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-3">Actions</h4>
              <div class="space-y-3">
                <Link
                  v-if="transaction.status === 'completed'"
                  :href="route('transactions.invoice', transaction.id)"
                  class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded block text-center"
                >
                  Download Invoice
                </Link>
                <Link
                  :href="route('automobiles.show', transaction.automobile.id)"
                  class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded block text-center"
                >
                  View Automobile
                </Link>
                <Link
                  v-if="transaction.booking"
                  :href="route('bookings.show', transaction.booking.id)"
                  class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded block text-center"
                >
                  View Booking
                </Link>
              </div>
            </div>

            <!-- Status History -->
            <div v-if="transaction.status !== 'pending'" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <h4 class="text-md font-medium text-gray-900 mb-3">Status History</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Created:</span>
                  <span class="text-gray-900">{{ formatDate(transaction.created_at) }}</span>
                </div>
                <div v-if="transaction.processed_at" class="flex justify-between">
                  <span class="text-gray-600">Last Updated:</span>
                  <span class="text-gray-900">{{ formatDate(transaction.processed_at) }}</span>
                </div>
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

export default {
  name: 'TransactionsShow',

  components: {
    AppLayout,
    Link
  },

  props: {
    transaction: {
      type: Object,
      required: true
    },
    canManage: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      statusForm: {
        status: this.transaction.status,
        notes: ''
      },
      statusProcessing: false,
      statusErrors: {}
    }
  },

  methods: {
    updateStatus() {
      this.statusProcessing = true
      this.statusErrors = {}

      Inertia.patch(route('transactions.updateStatus', this.transaction.id), this.statusForm, {
        onSuccess: () => {
          this.statusProcessing = false
          this.statusForm.notes = ''
        },
        onError: (errors) => {
          this.statusErrors = errors
          this.statusProcessing = false
        }
      })
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },

    formatPaymentMethod(method) {
      const methods = {
        'credit_card': 'Credit Card',
        'bank_transfer': 'Bank Transfer',
        'cash': 'Cash',
        'check': 'Check'
      }
      return methods[method] || method
    },

    statusClass(status) {
      const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'processing': 'bg-blue-100 text-blue-800',
        'completed': 'bg-green-100 text-green-800',
        'failed': 'bg-red-100 text-red-800',
        'refunded': 'bg-gray-100 text-gray-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
  }
}
</script>
