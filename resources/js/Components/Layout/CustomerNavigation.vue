<template>
  <div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <div class="flex-shrink-0 flex items-center">
            <Link :href="route('dashboard')" class="text-xl font-bold text-gray-900">
              AutoDealer
            </Link>
          </div>
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <Link
              :href="route('automobiles.index')"
              :class="[
                isActive('automobiles')
                  ? 'border-indigo-500 text-gray-900'
                  : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Browse Cars
            </Link>
            <Link
              :href="route('bookings.index')"
              :class="[
                isActive('bookings')
                  ? 'border-indigo-500 text-gray-900'
                  : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              My Bookings
              <span v-if="pendingBookings > 0" class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ pendingBookings }}
              </span>
            </Link>
            <Link
              :href="route('transactions.index')"
              :class="[
                isActive('transactions')
                  ? 'border-indigo-500 text-gray-900'
                  : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Transactions
            </Link>
          </div>
        </div>

        <div class="hidden sm:ml-6 sm:flex sm:items-center">
          <!-- Search -->
          <div class="max-w-lg w-full lg:max-w-xs">
            <label for="search" class="sr-only">Search</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
              </div>
              <input
                id="search"
                v-model="searchQuery"
                @keyup.enter="performSearch"
                name="search"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Search cars..."
                type="search"
              />
            </div>
          </div>

          <!-- Profile dropdown -->
          <div class="ml-3 relative">
            <div>
              <button
                @click="showProfileDropdown = !showProfileDropdown"
                type="button"
                class="bg-white flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                id="user-menu-button"
              >
                <span class="sr-only">Open user menu</span>
                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                  <span class="text-sm font-medium text-gray-700">
                    {{ user.name.charAt(0).toUpperCase() }}
                  </span>
                </div>
              </button>
            </div>

            <div
              v-show="showProfileDropdown"
              @click.away="showProfileDropdown = false"
              class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
            >
              <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                <div class="font-medium">{{ user.name }}</div>
                <div class="text-xs text-gray-500">{{ user.email }}</div>
              </div>
              <Link
                :href="route('profile.edit')"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                Profile Settings
              </Link>
              <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                Sign out
              </Link>
            </div>
          </div>
        </div>

        <!-- Mobile menu button -->
        <div class="-mr-2 flex items-center sm:hidden">
          <button
            @click="showMobileMenu = !showMobileMenu"
            type="button"
            class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
          >
            <span class="sr-only">Open main menu</span>
            <svg v-if="!showMobileMenu" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-show="showMobileMenu" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <Link
          :href="route('automobiles.index')"
          :class="[
            isActive('automobiles')
              ? 'bg-indigo-50 border-indigo-500 text-indigo-700'
              : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800',
            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium'
          ]"
        >
          Browse Cars
        </Link>
        <Link
          :href="route('bookings.index')"
          :class="[
            isActive('bookings')
              ? 'bg-indigo-50 border-indigo-500 text-indigo-700'
              : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800',
            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium'
          ]"
        >
          My Bookings
          <span v-if="pendingBookings > 0" class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
            {{ pendingBookings }}
          </span>
        </Link>
        <Link
          :href="route('transactions.index')"
          :class="[
            isActive('transactions')
              ? 'bg-indigo-50 border-indigo-500 text-indigo-700'
              : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800',
            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium'
          ]"
        >
          Transactions
        </Link>
      </div>
      <div class="pt-4 pb-3 border-t border-gray-200">
        <div class="flex items-center px-4">
          <div class="flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
              <span class="text-sm font-medium text-gray-700">
                {{ user.name.charAt(0).toUpperCase() }}
              </span>
            </div>
          </div>
          <div class="ml-3">
            <div class="text-base font-medium text-gray-800">{{ user.name }}</div>
            <div class="text-sm font-medium text-gray-500">{{ user.email }}</div>
          </div>
        </div>
        <div class="mt-3 space-y-1">
          <Link
            :href="route('profile.edit')"
            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
          >
            Profile Settings
          </Link>
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
          >
            Sign out
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import { Inertia } from '@inertiajs/inertia'

export default {
  name: 'CustomerNavigation',

  components: {
    Link
  },

  props: {
    user: {
      type: Object,
      required: true
    },
    pendingBookings: {
      type: Number,
      default: 0
    }
  },

  data() {
    return {
      showProfileDropdown: false,
      showMobileMenu: false,
      searchQuery: ''
    }
  },

  methods: {
    isActive(routeName) {
      return this.$page.component.toLowerCase().includes(routeName.toLowerCase()) ||
             this.$page.url.includes(routeName.replace('.', '/'))
    },

    performSearch() {
      if (this.searchQuery.trim()) {
        Inertia.visit(route('automobiles.index'), {
          method: 'get',
          data: { search: this.searchQuery },
          preserveState: true
        })
      }
    }
  },

  created() {
    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (!this.$el?.contains(e.target)) {
        this.showProfileDropdown = false
        this.showMobileMenu = false
      }
    })
  }
}
</script>
