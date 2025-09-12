<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ pageTitle }}
        </h2>
        <Link
          v-if="canCreate"
          :href="route('transactions.create')"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          New Transaction
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Filter Section -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2 xl:col-span-1">
              <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
              <input
                id="search"
                v-model="form.search"
                @input="search"
                type="text"
                placeholder="Transaction number, customer, automobile..."
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
            </div>

            <!-- Status Filter -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                id="status"
                v-model="form.status"
                @change="search"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="">All Statuses</option>
                <option v-for="status in statuses" :key="status" :value="status">
                  {{ status.charAt(0).toUpperCase() + status.slice(1) }}
                </option>
              </select>
            </div>

            <!-- Type Filter -->
            <div>
              <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
              <select
                id="type"
                v-model="form.type"
                @change="search"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="">All Types</option>
                <option v-for="type in types" :key="type" :value="type">
                  {{ type.charAt(0).toUpperCase() + type.slice(1) }}
                </option>
              </select>
            </div>

            <!-- Date Range -->
            <div class="lg:col-span-2 xl:col-span-1">
              <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
              <div class="flex space-x-2">
                <div class="flex-1">
                  <label for="date_from" class="sr-only">From</label>
                  <input
                    id="date_from"
                    v-model="form.date_from"
                    @change="search"
                    type="date"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
                <div class="flex-1">
                  <label for="date_to" class="sr-only">To</label>
                  <input
                    id="date_to"
                    v-model="form.date_to"
                    @change="search"
                    type="date"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Clear Filters -->
          <div v-if="hasActiveFilters" class="mt-4 flex justify-end">
            <button
              @click="clearFilters"
              class="text-sm text-gray-500 hover:text-gray-700"
            >
              Clear all filters
            </button>
          </div>
        </div>

        <!-- Results Summary -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 p-4">
          <div class="flex justify-between items-center text-sm text-gray-600">
            <span>
              Showing {{ transactions.from || 0 }} to {{ transactions.to || 0 }}
              of {{ transactions.total || 0 }} transactions
            </span>
            <div class="flex items-center space-x-4">
              <span class="font-medium">Total Amount:
                <span class="text-green-600">${{ formatCurrency(totalAmount) }}</span>
              </span>
            </div>
          </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Transaction
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Customer
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Automobile
                  </th>
                  <th v-if="showDealerColumn" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Dealer
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
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50">
                  <!-- Transaction Info -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900">{{ transaction.transaction_number }}</div>
                      <div class="text-gray-500">{{ transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1) }}</div>
                    </div>
                  </td>

                  <!-- Customer -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900">{{ transaction.user.name }}</div>
                      <div class="text-gray-500">{{ transaction.user.email }}</div>
                    </div>
                  </td>

                  <!-- Automobile -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900">
                        {{ transaction.automobile.year }} {{ transaction.automobile.make }} {{ transaction.automobile.model }}
                      </div>
                      <div class="text-gray-500">VIN: {{ transaction.automobile.vin }}</div>
                    </div>
                  </td>

                  <!-- Dealer -->
                  <td v-if="showDealerColumn" class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900">{{ transaction.automobile.dealer.name }}</div>
                      <div class="text-gray-500">{{ transaction.automobile.dealer.email }}</div>
                    </div>
                  </td>

                  <!-- Amount -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                      ${{ formatCurrency(transaction.amount) }}
                    </div>
                    <div v-if="transaction.payment_method" class="text-xs text-gray-500">
                      {{ formatPaymentMethod(transaction.payment_method) }}
                    </div>
                  </td>

                  <!-- Status -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="statusClass(transaction.status)"
                      class="px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1) }}
                    </span>
                  </td>

                  <!-- Date -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div>{{ formatDate(transaction.created_at) }}</div>
                    <div v-if="transaction.processed_at" class="text-xs text-gray-500">
                      Processed: {{ formatDate(transaction.processed_at) }}
                    </div>
                  </td>

                  <!-- Actions -->
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                      <Link
                        :href="route('transactions.show', transaction.id)"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        View
                      </Link>
                      <Link
                        v-if="transaction.status === 'completed'"
                        :href="route('transactions.invoice', transaction.id)"
                        class="text-green-600 hover:text-green-900"
                      >
                        Invoice
                      </Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-if="!transactions.data || transactions.data.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
            <p class="mt-1 text-sm text-gray-500">
              {{ hasActiveFilters ? 'Try adjusting your search criteria' : 'Get started by creating a new transaction' }}
            </p>
            <div v-if="canCreate && !hasActiveFilters" class="mt-6">
              <Link
                :href="route('transactions.create')"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Create Transaction
              </Link>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="transactions.data && transactions.data.length > 0" class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <Link
                  v-if="transactions.prev_page_url"
                  :href="transactions.prev_page_url"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Previous
                </Link>
                <Link
                  v-if="transactions.next_page_url"
                  :href="transactions.next_page_url"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Next
                </Link>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <template v-for="link in transactions.links">
                      <Link
                        v-if="link.url"
                        :key="link.label"
                        :href="link.url"
                        :class="[
                          link.active
                            ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                        ]"
                      >
                        <span v-if="link.label === '&laquo; Previous'">Previous</span>
                        <span v-else-if="link.label === 'Next &raquo;'">Next</span>
                        <span v-else>{{ link.label }}</span>
                      </Link>
                      <span
                        v-else
                        :key="`span-${link.label}`"
                        :class="[
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-default',
                          'bg-white border-gray-300 text-gray-500'
                        ]"
                      >
                        <span v-if="link.label === '&laquo; Previous'">Previous</span>
                        <span v-else-if="link.label === 'Next &raquo;'">Next</span>
                        <span v-else>{{ link.label }}</span>
                      </span>
                    </template>
                  </nav>
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
import { debounce } from 'lodash'

export default {
  name: 'TransactionsIndex',

  components: {
    AppLayout,
    Link
  },

  props: {
    transactions: {
      type: Object,
      required: true
    },
    filters: {
      type: Object,
      default: () => ({})
    },
    statuses: {
      type: Array,
      default: () => []
    },
    types: {
      type: Array,
      default: () => []
    },
    canCreate: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      form: {
        search: this.filters.search || '',
        status: this.filters.status || '',
        type: this.filters.type || '',
        date_from: this.filters.date_from || '',
        date_to: this.filters.date_to || ''
      }
    }
  },

  computed: {
    pageTitle() {
      const role = this.$page.props.auth.user.role
      if (role === 'admin') return 'All Transactions'
      if (role === 'dealer') return 'Dealer Transactions'
      return 'My Transactions'
    },

    showDealerColumn() {
      return this.$page.props.auth.user.role === 'admin'
    },

    hasActiveFilters() {
      return Object.values(this.form).some(value => value !== '')
    },

    totalAmount() {
      if (!this.transactions.data) return 0
      return this.transactions.data.reduce((total, transaction) => {
        if (['completed', 'processing'].includes(transaction.status)) {
          return total + parseFloat(transaction.amount)
        }
        return total
      }, 0)
    }
  },

  methods: {
    search: debounce(function() {
      Inertia.get(route('transactions.index'), this.form, {
        preserveState: true,
        replace: true
      })
    }, 300),

    clearFilters() {
      this.form = {
        search: '',
        status: '',
        type: '',
        date_from: '',
        date_to: ''
      }
      this.search()
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },

    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US').format(amount)
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
