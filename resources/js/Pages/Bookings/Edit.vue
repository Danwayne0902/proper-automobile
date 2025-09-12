<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Edit Booking #{{ booking.booking_number }}
        </h2>
        <div class="flex space-x-2">
          <Link
            :href="route('bookings.show', booking.id)"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            View
          </Link>
          <Link
            :href="route('bookings.index')"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            Back to List
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

              <!-- Automobile Information (Read-only) -->
              <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Automobile Information</h3>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                  <div class="flex items-center space-x-4">
                    <div class="flex-1">
                      <h4 class="font-medium text-gray-900">
                        {{ booking.automobile.year }} {{ booking.automobile.make }} {{ booking.automobile.model }}
                      </h4>
                      <p class="text-sm text-gray-600">
                        Dealer: {{ booking.automobile.dealer.name }}
                      </p>
                      <p class="text-sm text-gray-600">
                        Price: ${{ Number(booking.automobile.price).toLocaleString() }}
                      </p>
                      <p class="text-sm text-gray-600">
                        VIN: {{ booking.automobile.vin }}
                      </p>
                    </div>
                  </div>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                  * Automobile cannot be changed for existing bookings
                </p>
              </div>

              <!-- Current Booking Status -->
              <div class="md:col-span-2">
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm text-blue-700">
                        <span class="font-medium">Current Status:</span>
                        <span
                          :class="statusClass(booking.status)"
                          class="ml-2 px-2 py-1 text-xs font-semibold rounded-full"
                        >
                          {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
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
                  :disabled="!canEditType"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                  :class="{ 'border-red-300': errors.type }"
                >
                  <option value="">Select booking type</option>
                  <option value="test_drive">Test Drive</option>
                  <option value="reservation">Reservation</option>
                  <option value="inspection">Inspection</option>
                </select>
                <p v-if="errors.type" class="mt-1 text-sm text-red-600">{{ errors.type }}</p>
                <p v-if="!canEditType" class="mt-1 text-sm text-gray-500">
                  Type cannot be changed for confirmed bookings
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
                  :min="minDate"
                  required
                  :disabled="!canEditDate"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                  :class="{ 'border-red-300': errors.scheduled_at }"
                />
                <p v-if="errors.scheduled_at" class="mt-1 text-sm text-red-600">{{ errors.scheduled_at }}</p>
                <p v-if="!canEditDate" class="mt-1 text-sm text-gray-500">
                  Date cannot be changed for confirmed bookings
                </p>
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
                  :disabled="!canEditTime"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                  :class="{ 'border-red-300': errors.preferred_time }"
                >
                  <option value="">Select time</option>
                  <option value="Morning (9:00 AM - 12:00 PM)">Morning (9:00 AM - 12:00 PM)</option>
                  <option value="Afternoon (12:00 PM - 5:00 PM)">Afternoon (12:00 PM - 5:00 PM)</option>
                  <option value="Evening (5:00 PM - 8:00 PM)">Evening (5:00 PM - 8:00 PM)</option>
                  <option value="Flexible">Flexible</option>
                </select>
                <p v-if="errors.preferred_time" class="mt-1 text-sm text-red-600">{{ errors.preferred_time }}</p>
                <p v-if="!canEditTime" class="mt-1 text-sm text-gray-500">
                  Time cannot be changed for confirmed bookings
                </p>
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

              <!-- Booking History -->
              <div v-if="booking.updated_at !== booking.created_at" class="md:col-span-2 mt-6">
                <h4 class="text-md font-medium text-gray-900 mb-2">Booking History</h4>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                  <div class="text-sm text-gray-600 space-y-1">
                    <p><span class="font-medium">Created:</span> {{ formatDate(booking.created_at) }}</p>
                    <p><span class="font-medium">Last Updated:</span> {{ formatDate(booking.updated_at) }}</p>
                    <p v-if="booking.confirmed_at"><span class="font-medium">Confirmed:</span> {{ formatDate(booking.confirmed_at) }}</p>
                  </div>
                </div>
              </div>

              <!-- Important Information -->
              <div class="md:col-span-2 mt-6" v-if="canEdit">
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <h3 class="text-sm font-medium text-yellow-800">
                        Edit Restrictions
                      </h3>
                      <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                          <li>Only pending bookings can be fully edited</li>
                          <li>Confirmed bookings have limited edit options</li>
                          <li>Notes can always be updated</li>
                          <li>Contact the dealer for major changes to confirmed bookings</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Read-only message -->
              <div v-if="!canEdit" class="md:col-span-2 mt-6">
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <h3 class="text-sm font-medium text-red-800">
                        Cannot Edit Booking
                      </h3>
                      <div class="mt-2 text-sm text-red-700">
                        <p>This booking cannot be edited because it has been completed, cancelled, or you don't have permission to modify it.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Submit Buttons -->
              <div v-if="canEdit" class="md:col-span-2 mt-8 flex justify-end space-x-4">
                <Link
                  :href="route('bookings.show', booking.id)"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  {{ processing ? 'Updating Booking...' : 'Update Booking' }}
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
  name: 'BookingsEdit',

  components: {
    AppLayout,
    Link
  },

  props: {
    booking: {
      type: Object,
      required: true
    },
    canEdit: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      form: {
        type: this.booking.type,
        scheduled_at: this.booking.scheduled_at.split(' ')[0], // Extract date part
        preferred_time: this.booking.preferred_time,
        notes: this.booking.notes || ''
      },
      processing: false,
      errors: {}
    }
  },

  computed: {
    minDate() {
      // Allow editing past dates for existing bookings, but for new dates use tomorrow
      const today = new Date()
      const bookingDate = new Date(this.booking.scheduled_at)

      if (bookingDate < today) {
        // If the booking date is in the past, allow keeping it
        return this.booking.scheduled_at.split(' ')[0]
      }

      // For future dates, minimum is tomorrow
      const tomorrow = new Date()
      tomorrow.setDate(tomorrow.getDate() + 1)
      return tomorrow.toISOString().split('T')[0]
    },

    canEditType() {
      return this.canEdit && this.booking.status === 'pending'
    },

    canEditDate() {
      return this.canEdit && ['pending', 'confirmed'].includes(this.booking.status)
    },

    canEditTime() {
      return this.canEdit && ['pending', 'confirmed'].includes(this.booking.status)
    }
  },

  methods: {
    submit() {
      if (!this.canEdit) {
        return
      }

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

      Inertia.put(route('bookings.update', this.booking.id), formData, {
        onSuccess: () => {
          this.processing = false
          // Show success message with SweetAlert
          this.$swal.fire({
            title: 'Booking Updated!',
            text: 'Your booking has been updated successfully.',
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
            text: 'There was an error updating your booking. Please check the form and try again.',
            icon: 'error',
            confirmButtonText: 'OK'
          })
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
