<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          New Transaction
        </h2>
        <Link
          :href="route('transactions.index')"
          class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
        >
          Cancel
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

              <!-- Automobile Selection -->
              <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Automobile</h3>
              </div>

              <!-- Pre-selected Automobile -->
              <div v-if="automobile" class="md:col-span-2">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                  <div class="flex items-center space-x-4">
                    <div class="flex-1">
                      <h4 class="font-medium text-gray-900">
                        {{ automobile.year }} {{ automobile.make }} {{ automobile.model }}
                      </h4>
                      <p class="text-sm text-gray-600">
                        Dealer: {{ automobile.dealer.name }}
                      </p>
                      <p class="text-sm text-gray-600">
                        Price: ${{ Number(automobile.price).toLocaleString() }}
                      </p>
                    </div>
                    <button
                      type="button"
                      @click="clearSelectedAutomobile"
                      class="text-blue-600 hover:text-blue-800 text-sm"
                    >
                      Change
                    </button>
                  </div>
                </div>
                <input type="hidden" :value="automobile.id" name="automobile_id" />
              </div>

              <!-- Automobile Selection Dropdown -->
              <div v-else class="md:col-span-2">
                <label for="automobile_id" class="block text-sm font-medium text-gray-700 mb-1">
                  Choose Automobile <span class="text-red-500">*</span>
                </label>
                <select
                  id="automobile_id"
                  v-model="form.automobile_id"
                  required
                  @change="onAutomobileChange"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.automobile_id }"
                >
                  <option value="">Select an automobile</option>
                  <option
                    v-for="auto in availableAutomobiles"
                    :key="auto.id"
                    :value="auto.id"
                  >
                    {{ auto.year }} {{ auto.make }} {{ auto.model }} - ${{ Number(auto.price).toLocaleString() }}
                  </option>
                </select>
                <p v-if="errors.automobile_id" class="mt-1 text-sm text-red-600">{{ errors.automobile_id }}</p>
              </div>

              <!-- Booking Selection (Optional) -->
              <div class="md:col-span-2" v-if="availableBookings.length > 0">
                <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-1">
                  Related Booking (Optional)
                </label>
                <select
                  id="booking_id"
                  v-model="form.booking_id"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.booking_id }"
                >
                  <option value="">No related booking</option>
                  <option
                    v-for="booking in filteredBookings"
                    :key="booking.id"
                    :value="booking.id"
                  >
                    {{ booking.booking_number }} - {{ booking.type }}
                  </option>
                </select>
                <p v-if="errors.booking_id" class="mt-1 text-sm text-red-600">{{ errors.booking_id }}</p>
                <p class="mt-1 text-sm text-gray-500">
                  Link this transaction to an existing confirmed booking
                </p>
              </div>

              <!-- Transaction Details -->
              <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h3>
              </div>

              <!-- Transaction Type -->
              <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                  Transaction Type <span class="text-red-500">*</span>
                </label>
                <select
                  id="type"
                  v-model="form.type"
                  required
                  @change="onTypeChange"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.type }"
                >
                  <option value="">Select transaction type</option>
                  <option value="payment">Full Payment</option>
                  <option value="deposit">Deposit</option>
                  <option value="refund">Refund</option>
                </select>
                <p v-if="errors.type" class="mt-1 text-sm text-red-600">{{ errors.type }}</p>
                <p class="mt-1 text-sm text-gray-500">
                  • Payment: Full purchase payment<br>
                  • Deposit: Partial payment to reserve<br>
                  • Refund: Return of previous payment
                </p>
              </div>

              <!-- Amount -->
              <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                  Amount <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                  </div>
                  <input
                    id="amount"
                    v-model="form.amount"
                    type="number"
                    step="0.01"
                    min="1"
                    :max="maxAmount"
                    required
                    class="block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': errors.amount }"
                    placeholder="0.00"
                  />
                </div>
                <p v-if="errors.amount" class="mt-1 text-sm text-red-600">{{ errors.amount }}</p>
                <p v-if="selectedAutomobilePrice" class="mt-1 text-sm text-gray-500">
                  <span v-if="form.type === 'payment'">
                    Automobile price: ${{ Number(selectedAutomobilePrice).toLocaleString() }}
                  </span>
                  <span v-else-if="form.type === 'deposit'">
                    Maximum deposit (50%): ${{ Number(selectedAutomobilePrice * 0.5).toLocaleString() }}
                  </span>
                </p>
              </div>

              <!-- Payment Method -->
              <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                  Payment Method <span class="text-red-500">*</span>
                </label>
                <select
                  id="payment_method"
                  v-model="form.payment_method"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.payment_method }"
                >
                  <option value="">Select payment method</option>
                  <option value="credit_card">Credit Card</option>
                  <option value="bank_transfer">Bank Transfer</option>
                  <option value="cash">Cash</option>
                  <option value="check">Check</option>
                </select>
                <p v-if="errors.payment_method" class="mt-1 text-sm text-red-600">{{ errors.payment_method }}</p>
              </div>

              <!-- Description -->
              <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                  Description
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="3"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.description }"
                  placeholder="Additional transaction details..."
                ></textarea>
                <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
                <p class="mt-1 text-sm text-gray-500">Maximum 1000 characters</p>
              </div>

              <!-- Important Information -->
              <div class="md:col-span-2 mt-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <h3 class="text-sm font-medium text-yellow-800">
                        Transaction Information
                      </h3>
                      <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                          <li>Your transaction will be pending until processed by the dealer</li>
                          <li>Full payments will mark the automobile as sold</li>
                          <li>Deposits will reserve the automobile for you</li>
                          <li>You will receive an invoice once the transaction is completed</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Submit Buttons -->
              <div class="md:col-span-2 mt-8 flex justify-end space-x-4">
                <Link
                  :href="route('transactions.index')"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="processing || !form.automobile_id || !form.type || !form.amount || !form.payment_method"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  {{ processing ? 'Creating Transaction...' : 'Create Transaction' }}
                </button>
              </div>
            </div>
          </form>
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
  name: 'TransactionsCreate',

  components: {
    AppLayout,
    Link
  },

  props: {
    automobile: {
      type: Object,
      default: null
    },
    booking: {
      type: Object,
      default: null
    },
    availableAutomobiles: {
      type: Array,
      default: () => []
    },
    availableBookings: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      form: {
        automobile_id: this.automobile ? this.automobile.id : '',
        booking_id: this.booking ? this.booking.id : '',
        type: '',
        amount: '',
        payment_method: '',
        description: ''
      },
      processing: false,
      errors: {}
    }
  },

  computed: {
    selectedAutomobilePrice() {
      if (this.automobile) {
        return this.automobile.price
      }

      const selectedAuto = this.availableAutomobiles.find(auto => auto.id == this.form.automobile_id)
      return selectedAuto ? selectedAuto.price : null
    },

    maxAmount() {
      if (!this.selectedAutomobilePrice) return null

      if (this.form.type === 'payment') {
        return this.selectedAutomobilePrice
      } else if (this.form.type === 'deposit') {
        return this.selectedAutomobilePrice * 0.5
      }

      return null
    },

    filteredBookings() {
      if (!this.form.automobile_id) return this.availableBookings

      return this.availableBookings.filter(booking =>
        booking.automobile_id == this.form.automobile_id
      )
    }
  },

  methods: {
    submit() {
      this.processing = true
      this.errors = {}

      Inertia.post(route('transactions.store'), this.form, {
        onSuccess: () => {
          this.processing = false
        },
        onError: (errors) => {
          this.errors = errors
          this.processing = false
        }
      })
    },

    clearSelectedAutomobile() {
      this.form.automobile_id = ''
      this.form.booking_id = ''
    },

    onAutomobileChange() {
      // Clear booking if automobile changes
      this.form.booking_id = ''

      // Set suggested amount based on type
      if (this.form.type && this.selectedAutomobilePrice) {
        if (this.form.type === 'payment') {
          this.form.amount = this.selectedAutomobilePrice
        } else if (this.form.type === 'deposit') {
          this.form.amount = this.selectedAutomobilePrice * 0.2 // Suggest 20% deposit
        }
      }
    },

    onTypeChange() {
      // Set suggested amount based on type
      if (this.selectedAutomobilePrice) {
        if (this.form.type === 'payment') {
          this.form.amount = this.selectedAutomobilePrice
        } else if (this.form.type === 'deposit') {
          this.form.amount = this.selectedAutomobilePrice * 0.2 // Suggest 20% deposit
        } else {
          this.form.amount = ''
        }
      }
    }
  }
}
</script>
