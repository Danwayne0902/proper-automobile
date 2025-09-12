<template>
  <nav class="fixed top-0 left-0 right-0 bg-white shadow-lg border-b border-gray-200 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <!-- Sidebar Toggle (for admin/dealer) -->
          <button
            v-if="shouldShowSidebarToggle"
            @click="$emit('toggle-sidebar')"
            class="mr-4 p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden"
          >
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- Logo -->
          <div class="flex-shrink-0">
            <Link href="/" class="flex items-center">
              <img :src="logoUrl" alt="Proper Automobile" class="h-8 w-8 object-contain">
              <span class="ml-2 text-xl font-bold text-gray-900">Proper Automobile</span>
            </Link>
          </div>

          <!-- Main Navigation Links -->
          <div class="hidden md:ml-10 md:flex md:items-baseline md:space-x-8">
            <!-- Customer Navigation -->
            <template v-if="!user || user.role === 'customer'">
              <Link
                :href="route('automobiles.index')"
                class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                :class="{ 'bg-gray-900 text-white': $page.component.startsWith('Automobiles') }"
              >
                Browse Cars
              </Link>
              <Link
                v-if="user"
                :href="route('bookings.index')"
                class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                :class="{ 'bg-gray-900 text-white': $page.component.startsWith('Bookings') }"
              >
                My Bookings
              </Link>
            </template>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <!-- Search (for customers) -->
          <div v-if="!user || user.role === 'customer'" class="hidden md:block">
            <div class="relative">
              <input
                v-model="searchQuery"
                @keyup.enter="performSearch"
                type="text"
                placeholder="Search cars..."
                class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              >
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Theme Selector -->
          <div v-if="user" class="relative">
            <button
              @click="showThemeSelector = !showThemeSelector"
              class="p-2 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
              </svg>
            </button>

            <!-- Theme Selector Dropdown -->
            <div v-show="showThemeSelector" class="origin-top-right absolute right-0 mt-2 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
              <div class="py-1">
                <div class="px-4 py-2 text-sm font-medium text-gray-700 border-b">
                  Select Theme
                </div>

                <!-- Theme Options -->
                <button
                  @click="setTheme('light')"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  :class="{ 'bg-gray-200': currentTheme === 'light' }"
                >
                  Light Theme
                </button>
                <button
                  @click="setTheme('dark')"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  :class="{ 'bg-gray-200': currentTheme === 'dark' }"
                >
                  Dark Theme
                </button>
                <button
                  @click="setTheme('blue')"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  :class="{ 'bg-gray-200': currentTheme === 'blue' }"
                >
                  Blue Theme
                </button>
              </div>
            </div>
          </div>

          <!-- User Menu -->
          <div v-if="user" class="relative">
            <button
              @click="showUserMenu = !showUserMenu"
              class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                {{ user.name.charAt(0).toUpperCase() }}
              </div>
              <span class="ml-2 text-gray-700 font-medium">{{ user.name }}</span>
              <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>

            <!-- User Dropdown Menu -->
            <div v-show="showUserMenu" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
              <div class="py-1">
                <div class="px-4 py-2 text-xs text-gray-500 border-b">
                  {{ user.email }}
                  <div class="font-medium text-indigo-600 capitalize">{{ user.role }}</div>
                </div>
                <Link
                  :href="route('profile.edit')"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Profile Settings
                </Link>
                <button
                  @click="logout"
                  class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Sign Out
                </button>
              </div>
            </div>
          </div>

          <!-- Guest Links -->
          <div v-else class="flex items-center space-x-4">
            <Link
              :href="route('login')"
              class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
            >
              Sign In
            </Link>
            <Link
              :href="route('register')"
              class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700"
            >
              Register
            </Link>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue';

export default {
  name: 'Navbar',
  components: {
    Link
  },
  props: {
    user: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      showUserMenu: false,
      showThemeSelector: false,
      searchQuery: '',
      logoUrl: '/proper-autos.png',
      currentTheme: 'light'
    }
  },
  computed: {
    shouldShowSidebarToggle() {
      if (!this.user) return false
      return this.user.role === 'admin' || this.user.role === 'dealer'
    }
  },
  methods: {
    logout() {
      this.$inertia.post(route('logout'))
    },
    performSearch() {
      if (this.searchQuery.trim()) {
        this.$inertia.get(route('automobiles.index'), {
          search: this.searchQuery
        })
      }
    },
    setTheme(theme) {
      this.currentTheme = theme;
      this.showThemeSelector = false;

      // Apply theme to body
      document.body.className = '';
      document.body.classList.add(`theme-${theme}`);

      // Save theme preference to localStorage
      localStorage.setItem('theme', theme);

      // Dispatch event to notify other components
      window.dispatchEvent(new CustomEvent('theme-changed', { detail: theme }));
    },
    loadSavedTheme() {
      // Load theme from localStorage
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme) {
        this.currentTheme = savedTheme;
        document.body.classList.add(`theme-${savedTheme}`);
      }
    }
  },
  mounted() {
    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (!this.$el.contains(e.target)) {
        this.showUserMenu = false;
        this.showThemeSelector = false;
      }
    });

    // Load saved theme
    this.loadSavedTheme();
  }
}
</script>
