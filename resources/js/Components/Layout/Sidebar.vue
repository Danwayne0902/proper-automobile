<template>
  <div
    v-show="isOpen"
    class="fixed inset-0 flex z-40 lg:z-auto lg:static lg:inset-auto lg:flex-shrink-0"
  >
    <!-- Overlay for mobile -->
    <div
      v-show="isOpen"
      @click="$emit('close')"
      class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden"
    ></div>

    <!-- Sidebar -->
    <div class="relative flex-1 flex flex-col max-w-48 w-full bg-primary lg:flex-shrink-0 lg:flex">
      <!-- Close button for mobile -->
      <div class="absolute top-0 right-0 -mr-12 pt-2 lg:hidden">
        <button
          @click="$emit('close')"
          class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
        >
          <span class="sr-only">Close sidebar</span>
          <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
        <div class="flex-shrink-0 flex items-center px-4">
          <h2 class="text-lg font-semibold text-primary">
            {{ user.role === 'admin' ? 'Admin Panel' : 'Dealer Dashboard' }}
          </h2>
        </div>

        <!-- Navigation -->
        <nav class="mt-8 px-3 space-y-1">
          <!-- Dashboard -->
          <Link
            :href="route('dashboard')"
            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
            :class="isActive('dashboard') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
          >
            <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z" />
            </svg>
            Dashboard
          </Link>

          <!-- Automobiles Management -->
          <Link
            :href="route('automobiles.index')"
            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
            :class="isActive('automobiles') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
          >
            <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            {{ user.role === 'admin' ? 'All Automobiles' : 'My Automobiles' }}
          </Link>

          <!-- Bookings Management -->
          <Link
            :href="route('bookings.index')"
            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
            :class="isActive('bookings') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
          >
            <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v14a2 2 0 002 2z" />
            </svg>
            Bookings
          </Link>

          <!-- Transactions -->
          <Link
            :href="route('transactions.index')"
            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
            :class="isActive('transactions') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
          >
            <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Transactions
          </Link>

          <!-- Admin Only -->
          <template v-if="user.role === 'admin'">
            <div class="mt-8">
              <h3 class="px-3 text-xs font-semibold text-tertiary uppercase tracking-wider">
                Administration
              </h3>

              <!-- Add New Car -->
              <Link
                :href="route('automobiles.create')"
                class="mt-2 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
                :class="isActive('automobiles.create') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Car
              </Link>

              <!-- Users Management -->
              <Link
                :href="route('admin.users.index')"
                class="mt-2 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
                :class="isActive('admin.users') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                Users
              </Link>

              <!-- Reports -->
              <Link
                :href="route('admin.reports.index')"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
                :class="isActive('admin.reports') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Reports
              </Link>

              <!-- Settings -->
              <Link
                :href="route('admin.settings.index')"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
                :class="isActive('admin.settings') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
              </Link>
            </div>
          </template>

          <!-- Dealer Only -->
          <template v-if="user.role === 'dealer'">
            <div class="mt-8">
              <h3 class="px-3 text-xs font-semibold text-tertiary uppercase tracking-wider">
                Dealer Tools
              </h3>

              <!-- Add New Car -->
              <Link
                :href="route('automobiles.create')"
                class="mt-2 group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
                :class="isActive('automobiles.create') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Car
              </Link>

              <!-- Payment Confirmation -->
              <Link
                :href="route('reports.payments')"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
                :class="isActive('reports.payments') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Payment Confirmation
              </Link>

              <!-- My Profile -->
              <Link
                :href="route('profile.edit')"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors nav-link"
                :class="isActive('profile') ? 'bg-accent text-white' : 'text-secondary hover:bg-tertiary'"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
              </Link>
            </div>
          </template>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'

export default {
  name: 'Sidebar',
  components: {
    Link
  },
  props: {
    isOpen: {
      type: Boolean,
      default: false
    },
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    isActive(routeName) {
      try {
        // Get current route information
        const currentUrl = this.$page.url || ''
        const currentComponent = this.$page.component || ''

        // Handle different route patterns
        if (routeName === 'dashboard') {
          return currentUrl === '/dashboard' || currentComponent === 'Dashboard'
        }

        if (routeName === 'automobiles') {
          return currentUrl.includes('/automobiles') || currentComponent.includes('Automobiles')
        }

        if (routeName === 'bookings') {
          return currentUrl.includes('/bookings') || currentComponent.includes('Bookings')
        }

        if (routeName === 'transactions') {
          return currentUrl.includes('/transactions') || currentComponent.includes('Transactions')
        }

        if (routeName === 'automobiles.create') {
          return currentUrl.includes('/automobiles/create') || currentComponent.includes('Automobiles/Create')
        }

        if (routeName === 'profile') {
          return currentUrl.includes('/profile') || currentComponent.includes('Profile')
        }

        if (routeName === 'reports.payments') {
          return currentUrl.includes('/reports/payments') || currentComponent.includes('Admin/Reports/Payments')
        }

        // Handle admin routes
        if (routeName.includes('admin.')) {
          const adminSection = routeName.replace('admin.', '')
          return currentUrl.includes(`/admin/${adminSection}`) ||
                 currentComponent.includes(`Admin/${adminSection.charAt(0).toUpperCase() + adminSection.slice(1)}`)
        }

        return false
      } catch (error) {
        // Fallback to basic string matching if anything fails
        return this.$page.url.includes(routeName.replace('.', '/')) ||
               this.$page.component.toLowerCase().includes(routeName.toLowerCase())
      }
    }
  },
  mounted() {
    // Listen for theme changes
    window.addEventListener('theme-changed', () => {
      // This will trigger a re-render with updated classes
      this.$forceUpdate();
    });
  }
}
</script>
