<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Booking Details - {{ booking.booking_number }}
        </h2>
        <div class="flex space-x-2">
          <Link
            :href="route('bookings.index')"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            Back to List
          </Link>
          <Link
            v-if="canEdit"
            :href="route('bookings.edit', booking.id)"
            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
          >
            Edit
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          <!-- Main Booking Information -->
          <div class="lg:col-span-2 space-y-6">

            <!-- Booking Overview -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Booking Information</h3>
                <span
                  :class="statusClass(booking.status)"
                  class="px-3 py-1 text-sm font-semibold rounded-full"
                >
                  {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
                </span>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Booking Number</label>
                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ booking.booking_number }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ booking.type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Scheduled Date</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ formatDate(booking.scheduled_at) }}
                    </p>
                  </div>
                </div>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Preferred Time</label>
                    <p class="mt-1 text-sm text-gray-900">{{ booking.preferred_time }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Created</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ formatDateTime(booking.created_at) }}
                    </p>
                  </div>

                  <div v-if="booking.updated_at !== booking.created_at">
                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ formatDateTime(booking.updated_at) }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Notes -->
              <div v-if="booking.notes" class="mt-6">
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <div class="mt-1 p-3 bg-gray-50 rounded-md">
                  <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ booking.notes }}</p>
                </div>
              </div>
            </div>

            <!-- Automobile Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Automobile Details</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Vehicle</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900">
                      {{ booking.automobile.year }} {{ booking.automobile.make }} {{ booking.automobile.model }}
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">VIN</label>
                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ booking.automobile.vin }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <p class="mt-1 text-lg font-bold text-green-600">
                      ${{ Number(booking.automobile.price).toLocaleString() }}
                    </p>
                  </div>
                </div>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Mileage</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ Number(booking.automobile.mileage).toLocaleString() }} miles
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Color</label>
                    <p class="mt-1 text-sm text-gray-900">{{ booking.automobile.color }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Transmission</label>
                    <p class="mt-1 text-sm text-gray-900">{{ booking.automobile.transmission }}</p>
                  </div>
                </div>
              </div>

              <div class="mt-4">
                <Link
                  :href="route('automobiles.show', booking.automobile.id)"
                  class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                >
                  View Full Automobile Details →
                </Link>
              </div>
            </div>

          </div>

          <!-- Sidebar -->
          <div class="space-y-6">

            <!-- Customer/Dealer Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">
                {{ $page.props.auth.user.role === 'customer' ? 'Dealer Information' : 'Customer Information' }}
              </h3>

              <div v-if="$page.props.auth.user.role === 'customer'" class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Name</label>
                  <p class="mt-1 text-sm text-gray-900">{{ booking.automobile.dealer.name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <p class="mt-1 text-sm text-gray-900">{{ booking.automobile.dealer.email }}</p>
                </div>
                <div v-if="booking.automobile.dealer.phone">
                  <label class="block text-sm font-medium text-gray-700">Phone</label>
                  <p class="mt-1 text-sm text-gray-900">{{ booking.automobile.dealer.phone }}</p>
                </div>
              </div>

              <div v-else class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Name</label>
                  <p class="mt-1 text-sm text-gray-900">{{ booking.user.name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <p class="mt-1 text-sm text-gray-900">{{ booking.user.email }}</p>
                </div>
                <div v-if="booking.user.phone">
                  <label class="block text-sm font-medium text-gray-700">Phone</label>
                  <p class="mt-1 text-sm text-gray-900">{{ booking.user.phone }}</p>
                </div>
              </div>
            </div>

            <!-- Status Management (for dealers and admins) -->
            <div v-if="canManage" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Manage Booking</h3>

              <form @submit.prevent="updateStatus">
                <div class="space-y-4">
                  <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select
                      id="status"
                      v-model="statusForm.status"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                      <option value="pending">Pending</option>
                      <option value="confirmed">Confirmed</option>
                      <option value="completed">Completed</option>
                      <option value="cancelled">Cancelled</option>
                    </select>
                  </div>

                  <div>
                    <label for="status_notes" class="block text-sm font-medium text-gray-700">Status Notes</label>
                    <textarea
                      id="status_notes"
                      v-model="statusForm.notes"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="Optional notes about status change..."
                    ></textarea>
                  </div>

                  <button
                    type="submit"
                    :disabled="statusProcessing"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                  >
                    {{ statusProcessing ? 'Updating...' : 'Update Status' }}
                  </button>
                </div>
              </form>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>

              <div class="space-y-3">
                <Link
                  v-if="canEdit"
                  :href="route('bookings.edit', booking.id)"
                  class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center block"
                >
                  Edit Booking
                </Link>

                <button
                  v-if="canDelete"
                  @click="deleteBooking"
                  class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                >
                  Delete Booking
                </button>
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
  name: 'BookingsShow',

  components: {
    AppLayout,
    Link
  },

  props: {
    booking: {
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
        status: this.booking.status,
        notes: ''
      },
      statusProcessing: false
    }
  },

  mounted() {
    // Check if we should show the booking created alert
    if (this.$page.props.flash && this.$page.props.flash.success && this.$page.props.flash.showAlert) {
      this.$swal.fire({
        title: 'Booking Created!',
        text: this.$page.props.flash.success,
        icon: 'success',
        confirmButtonText: 'OK'
      })
    }
  },

  computed: {
    canEdit() {
      const user = this.$page.props.auth.user
      return user.role === 'customer' &&
             this.booking.user_id === user.id &&
             this.booking.status === 'pending'
    },

    canDelete() {
      const user = this.$page.props.auth.user
      return (user.role === 'admin') ||
             (user.role === 'customer' && this.booking.user_id === user.id &&
              ['pending', 'cancelled'].includes(this.booking.status)) ||
             (user.role === 'dealer' && this.booking.automobile.dealer_id === user.id &&
              ['pending', 'cancelled'].includes(this.booking.status))
    }
  },

  methods: {
    updateStatus() {
      this.statusProcessing = true

      Inertia.patch(route('bookings.updateStatus', this.booking.id), this.statusForm, {
        onSuccess: () => {
          this.statusProcessing = false
          this.statusForm.notes = ''
        },
        onError: () => {
          this.statusProcessing = false
        }
      })
    },

    deleteBooking() {
      const bookingType = this.booking.type.replace('_', ' ')
      if (confirm(`Are you sure you want to delete this ${bookingType} booking (${this.booking.booking_number})?`)) {
        Inertia.delete(route('bookings.destroy', this.booking.id))
      }
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long'
      })
    },

    formatDateTime(date) {
      return new Date(date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
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
