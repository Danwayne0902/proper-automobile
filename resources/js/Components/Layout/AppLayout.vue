<template>
  <div class="min-h-screen bg-primary pt-16">
    <!-- Custom Loader -->
    <CustomLoader :loading="isLoading" />

    <!-- Navbar -->
    <Navbar
      :user="$page.props.auth.user"
      @toggle-sidebar="toggleSidebar"
    />

    <!-- Main Content Area -->
    <div class="flex">
      <!-- Sidebar for Admin/Dealer -->
      <Sidebar
        v-if="shouldShowSidebar"
        :is-open="sidebarOpen"
        :user="$page.props.auth.user"
        @close="closeSidebar"
      />

      <!-- Main Content -->
      <main
        class="flex-1 transition-all duration-300 ease-in-out"
        :class="{ 'lg:ml-30': shouldShowSidebar && sidebarOpen, 'ml-0': !sidebarOpen || !shouldShowSidebar }"
      >
        <!-- Page Header -->
        <div v-if="title" class="bg-primary shadow border-b border-primary">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-primary">
              {{ title }}
            </h1>
          </div>
        </div>

        <!-- Flash Messages -->
        <div v-if="$page.props.flash && $page.props.flash.success" class="mx-4 mt-4">
          <div class="rounded-md bg-success p-4 border border-success">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-success">
                  {{ $page.props.flash.success }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <div v-if="$page.props.flash && $page.props.flash.error" class="mx-4 mt-4">
          <div class="rounded-md bg-error p-4 border border-error">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-error" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-error">
                  {{ $page.props.flash.error }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Page Content -->
        <div class="py-6">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <slot />
          </div>
        </div>
      </main>
    </div>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script>
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
import Footer from './Footer.vue'
import CustomLoader from '../CustomLoader.vue'
import { Inertia } from '@inertiajs/inertia'

export default {
  name: 'AppLayout',
  components: {
    Navbar,
    Sidebar,
    Footer,
    CustomLoader
  },
  props: {
    title: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      sidebarOpen: false,
      isLoading: false
    }
  },
  computed: {
    shouldShowSidebar() {
      const user = this.$page.props.auth.user
      if (!user) return false
      return user.role === 'admin' || user.role === 'dealer'
    }
  },
  methods: {
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen
    },
    closeSidebar() {
      this.sidebarOpen = false
    }
  },
  mounted() {
    // Auto-open sidebar on larger screens for admin/dealer
    if (this.shouldShowSidebar && window.innerWidth >= 1024) {
      this.sidebarOpen = true
    }

    // Set up Inertia loading events
    Inertia.on('start', () => {
      this.isLoading = true
    })

    Inertia.on('finish', () => {
      // Add a small delay to ensure smooth transition
      setTimeout(() => {
        this.isLoading = false
      }, 300)
    })

    // Show success alert if needed
    if (this.$page.props.flash && this.$page.props.flash.success && this.$page.props.flash.showAlert) {
      this.$nextTick(() => {
        this.$swal.fire({
          title: 'Success!',
          text: this.$page.props.flash.success,
          icon: 'success',
          confirmButtonText: 'OK'
        })
      })
    }

    // Apply saved theme on mount
    this.$nextTick(() => {
      const savedTheme = localStorage.getItem('theme') || 'light';
      document.body.className = '';
      document.body.classList.add(`theme-${savedTheme}`);
    });

    // Listen for theme changes
    window.addEventListener('theme-changed', (event) => {
      document.body.className = '';
      document.body.classList.add(`theme-${event.detail}`);
    });
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
