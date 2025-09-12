<template>
  <AppLayout :title="`Automobiles (${totalResults})`">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Automobiles
        </h2>
        <div class="flex space-x-2">
          <button
            @click="toggleFilters"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            {{ showAdvancedFilters ? 'Hide Filters' : 'Show Filters' }}
          </button>
          <Link
            v-if="$page.props.auth.user && ($page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'dealer')"
            :href="route('automobiles.create')"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
          >
            Add New
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Search and Filters -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Search</label>
                <input
                  v-model="form.search"
                  type="text"
                  placeholder="Make, Model, Year..."
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select
                  v-model="form.status"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                  <option value="">All Statuses</option>
                  <option
                    v-for="status in filterOptions.statuses"
                    :key="status"
                    :value="status"
                  >
                    {{ status.charAt(0).toUpperCase() + status.slice(1) }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Make</label>
                <select
                  v-model="form.make"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                  <option value="">All Makes</option>
                  <option
                    v-for="make in filterOptions.makes"
                    :key="make"
                    :value="make"
                  >
                    {{ make }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Sort By</label>
                <div class="flex space-x-1">
                  <select
                    v-model="form.sort_by"
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  >
                    <option value="created_at">Date Added</option>
                    <option value="price">Price</option>
                    <option value="year">Year</option>
                    <option value="mileage">Mileage</option>
                    <option value="make">Make</option>
                    <option value="model">Model</option>
                  </select>
                  <button
                    @click="toggleSortDirection"
                    class="px-2 rounded-md border border-gray-300"
                  >
                    <svg
                      v-if="form.sort_direction === 'asc'"
                      class="w-5 h-5"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    <svg
                      v-else
                      class="w-5 h-5"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Advanced Filters -->
            <div v-if="showAdvancedFilters" class="mt-4 pt-4 border-t border-gray-200">
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Body Type</label>
                  <select
                    v-model="form.body_type"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  >
                    <option value="">All Body Types</option>
                    <option
                      v-for="bodyType in filterOptions.body_types"
                      :key="bodyType"
                      :value="bodyType"
                    >
                      {{ bodyType }}
                    </option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Fuel Type</label>
                  <select
                    v-model="form.fuel_type"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  >
                    <option value="">All Fuel Types</option>
                    <option
                      v-for="fuelType in filterOptions.fuel_types"
                      :key="fuelType"
                      :value="fuelType"
                    >
                      {{ fuelType }}
                    </option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Transmission</label>
                  <select
                    v-model="form.transmission"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  >
                    <option value="">All Transmissions</option>
                    <option
                      v-for="transmission in filterOptions.transmissions"
                      :key="transmission"
                      :value="transmission"
                    >
                      {{ transmission }}
                    </option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Color</label>
                  <select
                    v-model="form.color"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  >
                    <option value="">All Colors</option>
                    <option
                      v-for="color in filterOptions.colors"
                      :key="color"
                      :value="color"
                    >
                      {{ color }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Min Price</label>
                  <input
                    v-model="form.min_price"
                    type="number"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Max Price</label>
                  <input
                    v-model="form.max_price"
                    type="number"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Min Year</label>
                  <input
                    v-model="form.min_year"
                    type="number"
                    :min="filterOptions.year_range.min"
                    :max="filterOptions.year_range.max"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Max Year</label>
                  <input
                    v-model="form.max_year"
                    type="number"
                    :min="filterOptions.year_range.min"
                    :max="filterOptions.year_range.max"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Min Mileage</label>
                  <input
                    v-model="form.min_mileage"
                    type="number"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Max Mileage</label>
                  <input
                    v-model="form.max_mileage"
                    type="number"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                  />
                </div>
                <div class="md:col-span-2 flex items-end">
                  <button
                    @click="clearFilters"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                  >
                    Clear Filters
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Results Count -->
        <div class="mb-4">
          <p class="text-sm text-gray-600">
            Showing {{ automobiles.from }} to {{ automobiles.to }} of {{ automobiles.total }} results
          </p>
        </div>

        <!-- Automobiles Grid -->
        <div v-if="automobiles.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="automobile in automobiles.data"
            :key="automobile.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
          >
            <div class="relative">
              <!-- Image -->
              <div class="h-48 bg-gray-200 relative">
                <img
                  v-if="automobile.images && automobile.images.length > 0"
                  :src="`/storage/${automobile.images[0]}`"
                  :alt="`${automobile.year} ${automobile.make} ${automobile.model}`"
                  class="w-full h-full object-cover"
                />
                <div v-else class="flex items-center justify-center h-full text-gray-500">
                  <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                  </svg>
                </div>
                <!-- Status Badge -->
                <div class="absolute top-2 right-2">
                  <span
                    :class="statusClass(automobile.status)"
                    class="px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ automobile.status.charAt(0).toUpperCase() + automobile.status.slice(1) }}
                  </span>
                </div>
              </div>

              <!-- Content -->
              <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                  <h3 class="text-lg font-semibold text-gray-900">
                    {{ automobile.year }} {{ automobile.make }} {{ automobile.model }}
                  </h3>
                </div>

                <p class="text-2xl font-bold text-green-600 mb-2">
                  ${{ Number(automobile.price).toLocaleString() }}
                </p>

                <div class="space-y-1 text-sm text-gray-600 mb-4">
                  <div class="flex justify-between">
                    <span>Mileage:</span>
                    <span>{{ Number(automobile.mileage).toLocaleString() }} miles</span>
                  </div>
                  <div class="flex justify-between">
                    <span>Color:</span>
                    <span>{{ automobile.color }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span>Transmission:</span>
                    <span>{{ automobile.transmission }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span>Fuel Type:</span>
                    <span>{{ automobile.fuel_type }}</span>
                  </div>
                </div>

                <!-- Dealer Info (for admins) -->
                <div v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin' && automobile.dealer" class="mb-3">
                  <p class="text-sm text-gray-500">
                    Dealer: {{ automobile.dealer.name }} (ID: {{ automobile.dealer_id }})
                  </p>
                </div>
                <!-- Dealer Info (for dealers) -->
                <div v-else-if="$page.props.auth.user && $page.props.auth.user.role === 'dealer'" class="mb-3">
                  <p class="text-sm text-gray-500">
                    Dealer ID: {{ automobile.dealer_id }} (Current Dealer ID: {{ $page.props.auth.user.id }})
                  </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                  <Link
                    v-if="$page.props.auth.user"
                    :href="route('automobiles.show', automobile.id)"
                    class="flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold h-10 w-10 rounded-full text-center"
                    title="View Details"
                  >
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </Link>

                  <Link
                    v-else
                    :href="route('automobiles.public.show', automobile.id)"
                    class="flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold h-10 w-10 rounded-full text-center"
                    title="View Details"
                  >
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </Link>

                  <Link
                    v-if="canEdit(automobile)"
                    :href="route('automobiles.edit', automobile.id)"
                    class="flex items-center justify-center bg-yellow-500 hover:bg-yellow-700 text-white font-bold h-10 w-10 rounded-full"
                    title="Edit"
                  >
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </Link>

                  <button
                    v-if="canEdit(automobile)"
                    @click="deleteAutomobile(automobile)"
                    class="flex items-center justify-center bg-red-500 hover:bg-red-700 text-white font-bold h-10 w-10 rounded-full"
                    title="Delete"
                  >
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
            <path d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No automobiles found</h3>
          <p class="mt-1 text-sm text-gray-500">Get started by adding a new automobile.</p>
          <div class="mt-6">
            <Link
              v-if="$page.props.auth.user && ($page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'dealer')"
              :href="route('automobiles.create')"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Add Automobile
            </Link>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="automobiles.data.length > 0" class="mt-6">
          <nav class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="hidden sm:block">
              <p class="text-sm text-gray-700">
                Showing {{ automobiles.from }} to {{ automobiles.to }} of {{ automobiles.total }} results
              </p>
            </div>
            <div class="flex-1 flex justify-between sm:justify-end">
              <Link
                v-if="automobiles.prev_page_url"
                :href="automobiles.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Previous
              </Link>
              <Link
                v-if="automobiles.next_page_url"
                :href="automobiles.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Next
              </Link>
            </div>
          </nav>
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
  name: 'AutomobilesIndex',

  components: {
    AppLayout,
    Link
  },

  mounted() {
    // Debug: Log the automobiles data received
    console.log('Automobiles data received:', this.automobiles);
    if (this.$page.props.auth && this.$page.props.auth.user) {
      console.log('Current user:', this.$page.props.auth.user);
    }
  },

  props: {
    automobiles: {
      type: Object,
      required: true
    },
    filters: {
      type: [Object, Array],
      default: () => ({})
    },
    filterOptions: {
      type: Object,
      default: () => ({})
    },
    totalResults: {
      type: Number,
      default: 0
    }
  },

  data() {
    return {
      showAdvancedFilters: false,
      form: {
        search: this.filters.search || '',
        status: this.filters.status || '',
        make: this.filters.make || '',
        body_type: this.filters.body_type || '',
        fuel_type: this.filters.fuel_type || '',
        transmission: this.filters.transmission || '',
        color: this.filters.color || '',
        min_price: this.filters.min_price || '',
        max_price: this.filters.max_price || '',
        min_year: this.filters.min_year || '',
        max_year: this.filters.max_year || '',
        min_mileage: this.filters.min_mileage || '',
        max_mileage: this.filters.max_mileage || '',
        sort_by: this.filters.sort_by || 'created_at',
        sort_direction: this.filters.sort_direction || 'desc'
      }
    }
  },

  watch: {
    form: {
      handler: function() {
        this.search();
      },
      deep: true
    }
  },

  methods: {
    search: debounce(function() {
      Inertia.get(route('automobiles.index'), this.form, {
        preserveState: true,
        replace: true
      })
    }, 300),

    toggleFilters() {
      this.showAdvancedFilters = !this.showAdvancedFilters
    },

    clearFilters() {
      this.form = {
        search: '',
        status: '',
        make: '',
        body_type: '',
        fuel_type: '',
        transmission: '',
        color: '',
        min_price: '',
        max_price: '',
        min_year: '',
        max_year: '',
        min_mileage: '',
        max_mileage: '',
        sort_by: 'created_at',
        sort_direction: 'desc'
      }
      this.search()
    },

    toggleSortDirection() {
      this.form.sort_direction = this.form.sort_direction === 'desc' ? 'asc' : 'desc'
      this.search()
    },

    canEdit(automobile) {
      if (!this.$page.props.auth.user) return false;
      const user = this.$page.props.auth.user
      return user.role === 'admin' || (user.role === 'dealer' && automobile.dealer_id === user.id)
    },

    deleteAutomobile(automobile) {
      if (confirm(`Are you sure you want to delete this ${automobile.year} ${automobile.make} ${automobile.model}?`)) {
        Inertia.delete(route('automobiles.destroy', automobile.id))
      }
    },

    statusClass(status) {
      const classes = {
        'available': 'bg-green-100 text-green-800',
        'sold': 'bg-red-100 text-red-800',
        'reserved': 'bg-yellow-100 text-yellow-800',
        'maintenance': 'bg-gray-100 text-gray-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }
  }
}
</script>
