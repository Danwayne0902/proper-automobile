<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          New Booking
        </h2>
        <Link
          :href="route('bookings.index')"
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
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.automobile_id }"
                >
                  <option value="">Select an automobile</option>
                  <optgroup v-for="group in groupedAutomobiles" :key="group.dealer" :label="`Dealer: ${group.dealer}`">
                    <option
                      v-for="auto in group.automobiles"
                      :key="auto.id"
                      :value="auto.id"
                    >
                      {{ auto.year }} {{ auto.make }} {{ auto.model }}
                    </option>
                  </optgroup>
                </select>
                <p v-if="errors.automobile_id" class="mt-1 text-sm text-red-600">{{ errors.automobile_id }}</p>
              </div>

              <!-- Booking Details -->
              <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h3>
              </div>

              <!-- Booking Type -->
              <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                  Booking Type <span class="text-red-500">*</span>
                </label>
                <select
                  id="type"
                  v-model="form.type"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.type }"
                >
                  <option value="">Select booking type</option>
                  <option value="test_drive">Test Drive</option>
                  <option value="reservation">Reservation</option>
                  <option value="inspection">Inspection</option>
                </select>
                <p v-if="errors.type" class="mt-1 text-sm text-red-600">{{ errors.type }}</p>
                <p class="mt-1 text-sm text-gray-500">
                  • Test Drive: Schedule a drive to test the vehicle<br>
                  • Reservation: Reserve the vehicle for purchase<br>
                  • Inspection: Schedule a detailed inspection
                </p>
              </div>

              <!-- Scheduled Date -->
              <div>
                <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">
                  Preferred Date <span class="text-red-500">*</span>
                </label>
                <input
                  id="scheduled_at"
                  v-model="form.scheduled_at"
                  type="date"
                  :min="tomorrow"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.scheduled_at }"
                />
                <p v-if="errors.scheduled_at" class="mt-1 text-sm text-red-600">{{ errors.scheduled_at }}</p>
              </div>

              <!-- Preferred Time -->
              <div>
                <label for="preferred_time" class="block text-sm font-medium text-gray-700 mb-1">
                  Preferred Time <span class="text-red-500">*</span>
                </label>
                <select
                  id="preferred_time"
                  v-model="form.preferred_time"
                  required
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.preferred_time }"
                >
                  <option value="">Select time</option>
                  <option value="Morning (9:00 AM - 12:00 PM)">Morning (9:00 AM - 12:00 PM)</option>
                  <option value="Afternoon (12:00 PM - 5:00 PM)">Afternoon (12:00 PM - 5:00 PM)</option>
                  <option value="Evening (5:00 PM - 8:00 PM)">Evening (5:00 PM - 8:00 PM)</option>
                  <option value="Flexible">Flexible</option>
                </select>
                <p v-if="errors.preferred_time" class="mt-1 text-sm text-red-600">{{ errors.preferred_time }}</p>
              </div>

              <!-- Notes -->
              <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                  Additional Notes
                </label>
                <textarea
                  id="notes"
                  v-model="form.notes"
                  rows="4"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.notes }"
                  placeholder="Any special requests or information you'd like to share..."
                ></textarea>
                <p v-if="errors.notes" class="mt-1 text-sm text-red-600">{{ errors.notes }}</p>
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
                        Important Information
                      </h3>
                      <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                          <li>Your booking will be pending until confirmed by the dealer</li>
                          <li>For test drives, please bring a valid driver's license</li>
                          <li>You can cancel or modify pending bookings</li>
                          <li>The dealer will contact you to confirm the exact time and details</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Submit Buttons -->
              <div class="md:col-span-2 mt-8 flex justify-end space-x-4">
                <Link
                  :href="route('bookings.index')"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  {{ processing ? 'Creating Booking...' : 'Create Booking' }}
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
  name: 'BookingsCreate',

  components: {
    AppLayout,
    Link
  },

  props: {
    automobile: {
      type: Object,
      default: null
    },
    availableAutomobiles: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      form: {
        automobile_id: this.automobile ? this.automobile.id : '',
        type: '',
        scheduled_at: '',
        preferred_time: '',
        notes: ''
      },
      processing: false,
      errors: {}
    }
  },

  computed: {
    tomorrow() {
      const date = new Date()
      date.setDate(date.getDate() + 1)
      return date.toISOString().split('T')[0]
    },

    groupedAutomobiles() {
      const grouped = {}
      this.availableAutomobiles.forEach(auto => {
        const dealerName = auto.dealer?.name || 'Unknown Dealer'
        if (!grouped[dealerName]) {
          grouped[dealerName] = []
        }
        grouped[dealerName].push(auto)
      })

      return Object.keys(grouped).map(dealer => ({
        dealer,
        automobiles: grouped[dealer]
      }))
    }
  },

  methods: {
    submit() {
      this.processing = true
      this.errors = {}

      // Ensure the date is properly formatted
      const formData = { ...this.form }

      // Format the date properly if it exists
      if (this.form.scheduled_at) {
        // The HTML date input returns YYYY-MM-DD, we need to convert it to a full datetime
        // by adding a time component (using midnight as default)
        formData.scheduled_at = this.form.scheduled_at + ' 00:00:00'
      }

      Inertia.post(route('bookings.store'), formData, {
        onSuccess: () => {
          this.processing = false
          // Show success message with SweetAlert
          this.$swal.fire({
            title: 'Booking Created!',
            text: 'Your booking has been created successfully and is pending confirmation.',
            icon: 'success',
            confirmButtonText: 'OK'
          })
        },
        onError: (errors) => {
          this.errors = errors
          this.processing = false
          // Show error message with SweetAlert
          this.$swal.fire({
            title: 'Error!',
            text: 'There was an error creating your booking. Please check the form and try again.',
            icon: 'error',
            confirmButtonText: 'OK'
          })
        }
      })
    },

    clearSelectedAutomobile() {
      this.form.automobile_id = ''
    }
  }
}
</script>
