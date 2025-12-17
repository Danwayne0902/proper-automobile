<script>
import { Head, Link } from '@inertiajs/inertia-vue';
import { Inertia } from '@inertiajs/inertia';
import MapComponent from '@/Components/MapComponent.vue';

export default {
    name: 'Welcome',
    components: {
        Head,
        Link,
        MapComponent
    },
    props: {
        canLogin: {
            type: Boolean,
            default: false
        },
        canRegister: {
            type: Boolean,
            default: false
        },
        laravelVersion: {
            type: String,
            required: true,
        },
        phpVersion: {
            type: String,
            required: true,
        },
        featuredCars: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            mobileMenuOpen: false,
            logoUrl: '/proper-autos.png'
        }
    },
    methods: {
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen
        },
        formatPrice(price) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(price)
        },
        handleImageError(event) {
            // Fallback to a placeholder image if the car image fails to load
            event.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjI1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZTVlN2ViIi8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzZiNzI4MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkNhciBJbWFnZTwvdGV4dD4KICA8cGF0aCBkPSJtMTAwIDEwMCA2MCAzMGMxMCAzIDIwIDMgMzAgMGw2MC0zMGMxMC0zIDEwLTcgMC0xMGwtNjAtMzBjLTEwLTMtMjAtMy0zMCAwbC02MCAzMGMtMTAgyIDEwNzcgMCB6IiBmaWxsD0iIzliOWI5YiIvPgo8L3N2Zz4K'
        },
        scrollToSection(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        },
        viewCarDetails(car) {
            // If user is authenticated, go to automobiles page with filter
            if (this.$page.props.auth && this.$page.props.auth.user) {
                Inertia.get(route('automobiles.index'), {
                    search: `${car.make} ${car.model}`
                });
            } else {
                // Redirect to public car view using the actual automobile ID
                Inertia.get(route('automobiles.public.show', car.id));
            }
        },
        scheduleTestDrive(car) {
            // If user is authenticated, go to booking creation
            if (this.$page.props.auth && this.$page.props.auth.user) {
                Inertia.get(route('bookings.create'), {
                    automobile_make: car.make,
                    automobile_model: car.model
                });
            } else {
                // Redirect to registration/login
                Inertia.get(route('register'));
            }
        }
    }
};
</script>

<template>
  <div>
    <Head title="Proper Automobile - Premier Luxury Car Dealership" />

    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <img :src="logoUrl" alt="Proper Automobile" class="h-8 w-8 object-contain mr-2">
                <span class="text-blue-600">Proper</span> Automobile
              </h1>
            </div>
            <div class="hidden md:ml-10 md:flex md:space-x-8">
              <a @click="scrollToSection('home')" href="#home" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors cursor-pointer">
                Home
              </a>
              <a @click="scrollToSection('inventory')" href="#inventory" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors cursor-pointer">
                Inventory
              </a>
              <a @click="scrollToSection('services')" href="#services" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors cursor-pointer">
                Services
              </a>
              <a @click="scrollToSection('contact')" href="#contact" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors cursor-pointer">
                Contact
              </a>
            </div>
          </div>

          <div class="hidden md:flex md:items-center md:space-x-4">
            <template v-if="canLogin">
              <template v-if="$page.props.auth && $page.props.auth.user">
                <Link
                  :href="route('dashboard')"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                >
                  Dashboard
                </Link>
              </template>
              <template v-else>
                <Link
                  :href="route('login')"
                  class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors"
                >
                  Login
                </Link>
                <Link
                  v-if="canRegister"
                  :href="route('register')"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                >
                  Register
                </Link>
              </template>
            </template>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center">
            <button
              @click="toggleMobileMenu"
              class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative bg-gradient-to-r from-gray-900 to-blue-900 text-white pt-16">
      <div class="absolute inset-0 bg-black opacity-50"></div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
        <div class="text-center">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-tight mb-6">
            Find Your
            <span class="text-blue-400">Perfect</span>
            <br>
            Luxury Vehicle
          </h1>
          <p class="text-xl sm:text-2xl mb-8 max-w-3xl mx-auto text-gray-200">
            Discover premium automobiles from the world's most prestigious brands.
            Experience excellence in every drive.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a @click="scrollToSection('inventory')" href="#inventory" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-semibold transition-colors cursor-pointer">
              Browse Inventory
            </a>
            <a @click="scrollToSection('contact')" href="#contact" class="border border-white text-white hover:bg-white hover:text-gray-900 px-8 py-3 rounded-lg text-lg font-semibold transition-colors cursor-pointer">
              Schedule Test Drive
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Vehicles -->
    <section id="inventory" class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            Featured Vehicles
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Explore our handpicked selection of premium automobiles from renowned manufacturers
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="car in featuredCars"
            :key="car.id"
            class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
          >
            <div class="relative">
              <img
                :src="car.image"
                :alt="`${car.year} ${car.make} ${car.model}`"
                class="w-full h-48 object-cover"
                @error="handleImageError"
              >
              <div class="absolute top-4 right-4">
                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                  {{ car.status.charAt(0).toUpperCase() + car.status.slice(1) }}
                </span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-900 mb-2">
                {{ car.year }} {{ car.make }} {{ car.model }}
              </h3>
              <p class="text-2xl font-bold text-blue-600 mb-4">
                {{ formatPrice(car.price) }}
              </p>
              <div class="flex space-x-3">
                <button @click="viewCarDetails(car)" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                  View Details
                </button>
                <button @click="scheduleTestDrive(car)" class="flex-1 border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-md font-medium transition-colors">
                  Test Drive
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="text-center mt-12">
          <template v-if="canLogin && (!$page.props.auth || !$page.props.auth.user)">
            <Link
              :href="route('register')"
              class="inline-block bg-gray-900 hover:bg-black text-white px-8 py-3 rounded-lg text-lg font-semibold transition-colors"
            >
              View Full Inventory
            </Link>
          </template>
          <template v-else-if="$page.props.auth && $page.props.auth.user">
            <Link
              :href="route('automobiles.index')"
              class="inline-block bg-gray-900 hover:bg-black text-white px-8 py-3 rounded-lg text-lg font-semibold transition-colors"
            >
              Browse All Vehicles
            </Link>
          </template>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            Our Services
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            From test drives to financing, we provide comprehensive automotive services
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Test Drives -->
          <div class="text-center p-6">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Test Drives</h3>
            <p class="text-gray-600 mb-4">
              Experience your dream car firsthand with our hassle-free test drive scheduling
            </p>
            <button @click="scrollToSection('contact')" class="text-blue-600 hover:text-blue-800 font-medium">
              Schedule Now →
            </button>
          </div>

          <!-- Quality Inspection -->
          <div class="text-center p-6">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Quality Inspection</h3>
            <p class="text-gray-600 mb-4">
              Every vehicle undergoes rigorous inspection to ensure top quality and reliability
            </p>
            <Link :href="route('about')" class="text-green-600 hover:text-green-800 font-medium">
              Learn More →
            </Link>
          </div>

          <!-- Financing -->
          <div class="text-center p-6">
            <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Financing Options</h3>
            <p class="text-gray-600 mb-4">
              Flexible financing solutions to help you drive away in your perfect vehicle
            </p>
            <Link :href="route('contact')" class="text-purple-600 hover:text-purple-800 font-medium">
              Get Quote →
            </Link>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            Contact Us
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Ready to find your perfect vehicle? Get in touch with our expert team
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <!-- Contact Information -->
          <div class="space-y-8">
            <!-- Address -->
            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900">Visit Our Showroom</h3>
                <p class="text-gray-600 mt-1">
                  123 Luxury Auto Drive<br>
                  Premium District<br>
                  Car City, CC 12345
                </p>
              </div>
            </div>

            <!-- Phone -->
            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900">Call Us</h3>
                <p class="text-gray-600 mt-1">
                  Sales: +234 (705) 340-4846<br>
                  Service: +234 (703) 948-1762<br>
                  Mon-Fri: 9 AM - 8 PM<br>
                  Sat-Sun: 10 AM - 6 PM
                </p>
              </div>
            </div>

            <!-- Email -->
            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900">Email Us</h3>
                <p class="text-gray-600 mt-1">
                  <a href="mailto:info@properautomobile.com" class="text-blue-600 hover:text-blue-800">
                    info@properautomobile.com
                  </a><br>
                  <a href="mailto:sales@properautomobile.com" class="text-blue-600 hover:text-blue-800">
                    sales@properautomobile.com
                  </a>
                </p>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-white p-8 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Quick Actions</h3>
            <div class="space-y-4">
              <!-- Browse Inventory -->
              <template v-if="$page.props.auth && $page.props.auth.user">
                <Link
                  :href="route('automobiles.index')"
                  class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors"
                >
                  Browse Our Inventory
                </Link>
              </template>
              <template v-else>
                <Link
                  :href="route('register')"
                  class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors"
                >
                  Browse Our Inventory
                </Link>
              </template>

              <!-- Schedule Test Drive -->
              <template v-if="$page.props.auth && $page.props.auth.user">
                <Link
                  :href="route('bookings.create')"
                  class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors"
                >
                  Schedule Test Drive
                </Link>
              </template>
              <template v-else>
                <Link
                  :href="route('register')"
                  class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors"
                >
                  Schedule Test Drive
                </Link>
              </template>

              <!-- Contact Form -->
              <Link
                :href="route('contact')"
                class="block w-full text-center bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors"
              >
                Send Message
              </Link>
            </div>

            <!-- Map Placeholder -->
            <div class="mt-8">
              <h4 class="text-lg font-semibold text-gray-900 mb-4">Find Us</h4>
              <div class="h-48 rounded-lg overflow-hidden">
                <MapComponent
                  address="123 Luxury Auto Drive, Premium District, Car City, CC 12345"
                  title="Proper Automobile Location"
                  height="100%"
                  width="100%" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-600 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
          Ready to Find Your Dream Car?
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
          Join thousands of satisfied customers who found their perfect vehicle with us
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <template v-if="canLogin && (!$page.props.auth || !$page.props.auth.user)">
            <Link
              :href="route('register')"
              class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition-colors"
            >
              Get Started Today
            </Link>
          </template>
          <template v-else-if="$page.props.auth && $page.props.auth.user">
            <Link
              :href="route('automobiles.index')"
              class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition-colors"
            >
              Browse Inventory
            </Link>
          </template>
          <a @click="scrollToSection('contact')" href="#contact" class="border border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold transition-colors cursor-pointer">
            Contact Sales
          </a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <div class="flex items-center justify-center mb-4">
            <img :src="logoUrl" alt="Proper Automobile" class="h-8 w-8 object-contain mr-2">
            <h3 class="text-2xl font-bold">
              <span class="text-blue-400">Proper</span> Automobile
            </h3>
          </div>
          <p class="text-gray-400 mb-4">
            Your premier destination for luxury vehicles.
          </p>
          <p class="text-gray-400">
            © 2024 Proper Automobile. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>
