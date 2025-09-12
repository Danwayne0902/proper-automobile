<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Payment Confirmation
        </h2>
        <div class="text-sm text-gray-500">
          Review and confirm pending payments
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pending Payments</h3>
            <p class="mt-1 text-sm text-gray-500">
              Review and confirm payments from customers for your vehicles.
            </p>
          </div>

          <div class="p-6">
            <!-- Filters -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4">
              <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Search by transaction number, customer name, or vehicle..."
                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  @input="applyFilters"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select
                  v-model="filters.type"
                  class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  @change="applyFilters"
                >
                  <option value="">All Types</option>
                  <option value="payment">Payment</option>
                  <option value="deposit">Deposit</option>
                </select>
              </div>
            </div>

            <!-- Transactions Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Transaction
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Customer
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Vehicle
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Amount
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ transaction.transaction_number }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ transaction.description || 'No description' }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">
                              {{ transaction.user.name.charAt(0).toUpperCase() }}
                            </span>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{ transaction.user.name }}
                          </div>
                          <div class="text-sm text-gray-500">
                            {{ transaction.user.email }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ transaction.automobile.make }} {{ transaction.automobile.model }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ transaction.automobile.year }}
                      </div>
                      <div v-if="transaction.bankAccount" class="text-xs text-gray-400 mt-1">
                        Account: {{ transaction.bankAccount.bank_name }} (****{{ transaction.bankAccount.account_number.slice(-4) }})
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ${{ formatCurrency(transaction.amount) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ transaction.type }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(transaction.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button
                          @click="openConfirmModal(transaction, 'completed')"
                          class="text-green-600 hover:text-green-900"
                        >
                          Confirm
                        </button>
                        <button
                          @click="openConfirmModal(transaction, 'failed')"
                          class="text-red-600 hover:text-red-900"
                        >
                          Reject
                        </button>
                        <a
                          :href="route('transactions.show', transaction.id)"
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          View
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Empty State -->
                  <tr v-if="transactions.data.length === 0">
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                      No pending payments found.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between">
              <div class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ transactions.from || 0 }}</span>
                to
                <span class="font-medium">{{ transactions.to || 0 }}</span>
                of
                <span class="font-medium">{{ transactions.total }}</span>
                results
              </div>
              <div class="flex space-x-2">
                <template v-for="(link, index) in transactions.links">
                  <Link
                    v-if="link.url"
                    :key="`link-${index}`"
                    :href="link.url"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md',
                      link.active
                        ? 'z-10 bg-indigo-600 text-white'
                        : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'
                    ]"
                  >
                    {{ link.label }}
                  </Link>
                  <span
                    v-else
                    :key="`span-${index}`"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed"
                  >
                    {{ link.label }}
                  </span>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                :class="confirmAction === 'completed' ? 'bg-green-100' : 'bg-red-100'">
                <svg v-if="confirmAction === 'completed'" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg v-else class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ confirmAction === 'completed' ? 'Confirm Payment' : 'Reject Payment' }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to {{ confirmAction === 'completed' ? 'confirm' : 'reject' }} this payment?
                  </p>
                  <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm">
                      <div class="font-medium">{{ selectedTransaction?.transaction_number }}</div>
                      <div class="mt-1">{{ selectedTransaction?.user?.name }}</div>
                      <div class="mt-1 font-medium">${{ selectedTransaction ? formatCurrency(selectedTransaction.amount) : '0.00' }}</div>
                    </div>
                  </div>
                </div>
                <div class="mt-4">
                  <label for="notes" class="block text-sm font-medium text-gray-700">
                    Notes (Optional)
                  </label>
                  <textarea
                    id="notes"
                    v-model="confirmationNotes"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Add any notes about this confirmation..."
                  ></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="confirmPayment"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
              :class="confirmAction === 'completed' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500'"
            >
              {{ confirmAction === 'completed' ? 'Confirm' : 'Reject' }}
            </button>
            <button
              type="button"
              @click="closeConfirmModal"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from '@/Components/Layout/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue'
import { Inertia } from '@inertiajs/inertia'
import { debounce } from 'lodash'

export default {
  name: 'AdminReportsPayments',

  components: {
    AppLayout,
    Link
  },

  props: {
    transactions: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      showConfirmModal: false,
      selectedTransaction: null,
      confirmAction: 'completed',
      confirmationNotes: '',
      filters: {
        search: '',
        type: ''
      }
    }
  },

  methods: {
    openConfirmModal(transaction, action) {
      this.selectedTransaction = transaction
      this.confirmAction = action
      this.confirmationNotes = ''
      this.showConfirmModal = true
    },

    closeConfirmModal() {
      this.showConfirmModal = false
      this.selectedTransaction = null
      this.confirmAction = 'completed'
      this.confirmationNotes = ''
    },

    confirmPayment() {
      Inertia.patch(
        route('reports.payments.confirm', this.selectedTransaction.id),
        {
          status: this.confirmAction,
          notes: this.confirmationNotes
        },
        {
          onSuccess: () => {
            this.closeConfirmModal()
          }
        }
      )
    },

    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount)
    },

    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    },

    applyFilters: debounce(function() {
      Inertia.get(
        route('reports.payments'),
        {
          search: this.filters.search,
          type: this.filters.type
        },
        {
          preserveState: true,
          replace: true
        }
      )
    }, 300)
  }
}
</script>
