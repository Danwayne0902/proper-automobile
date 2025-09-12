<template>
  <div>
    <Head title="About Us - Proper Automobile" />

    <AppLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          About Us
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8">
              <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl font-bold mb-4">About Proper Automobile</h1>
                <p class="text-xl text-blue-100">
                  Your trusted partner in finding the perfect luxury vehicle for over a decade
                </p>
              </div>
            </div>

            <!-- Main Content -->
            <div class="p-8">
              <div class="max-w-4xl mx-auto">

                <!-- Our Story -->
                <div class="mb-12">
                  <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
                  <div class="prose prose-lg text-gray-600">
                    <p class="mb-4">
                      Founded in 2010, Proper Automobile has been dedicated to connecting discerning customers
                      with premium automobiles from the world's most prestigious brands. What started as a small
                      family business has grown into one of the region's most trusted luxury car dealerships.
                    </p>
                    <p class="mb-4">
                      We understand that purchasing a vehicle is more than just a transaction—it's an investment
                      in your lifestyle, your dreams, and your future. That's why we've built our reputation on
                      integrity, expertise, and exceptional customer service.
                    </p>
                    <p>
                      Today, we proudly serve customers across the country with our extensive inventory of
                      luxury vehicles, comprehensive financing options, and unparalleled after-sales support.
                    </p>
                  </div>
                </div>

                <!-- Our Mission -->
                <div class="mb-12">
                  <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
                  <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-lg text-gray-700 italic text-center">
                      "To provide an exceptional car buying experience by offering premium vehicles,
                      transparent pricing, and personalized service that exceeds our customers' expectations."
                    </p>
                  </div>
                </div>

                <!-- Our Values -->
                <div class="mb-12">
                  <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Values</h2>
                  <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                      <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                      </div>
                      <h3 class="text-xl font-semibold text-gray-900 mb-2">Integrity</h3>
                      <p class="text-gray-600">
                        We believe in honest, transparent dealings with every customer. No hidden fees, no pressure tactics.
                      </p>
                    </div>

                    <div class="text-center">
                      <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                      </div>
                      <h3 class="text-xl font-semibold text-gray-900 mb-2">Excellence</h3>
                      <p class="text-gray-600">
                        We strive for excellence in every aspect of our business, from vehicle selection to customer service.
                      </p>
                    </div>

                    <div class="text-center">
                      <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                      </div>
                      <h3 class="text-xl font-semibold text-gray-900 mb-2">Passion</h3>
                      <p class="text-gray-600">
                        Our love for automobiles drives us to find the perfect match between customer and vehicle.
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Why Choose Us -->
                <div class="mb-12">
                  <h2 class="text-3xl font-bold text-gray-900 mb-6">Why Choose Proper Automobile?</h2>
                  <div class="grid md:grid-cols-2 gap-8">
                    <div>
                      <ul class="space-y-4">
                        <li class="flex items-start">
                          <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                          <span class="text-gray-700">Carefully curated inventory of premium vehicles</span>
                        </li>
                        <li class="flex items-start">
                          <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                          <span class="text-gray-700">Comprehensive vehicle history reports</span>
                        </li>
                        <li class="flex items-start">
                          <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                          <span class="text-gray-700">Competitive financing options</span>
                        </li>
                        <li class="flex items-start">
                          <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                          <span class="text-gray-700">Expert sales and service team</span>
                        </li>
                      </ul>
                    </div>
                    <div>
                      <ul class="space-y-4">
                        <li class="flex items-start">
                          <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                          <span class="text-gray-700">Transparent pricing with no hidden fees</span>
                        </li>
                        <li class="flex items-start">
                          <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                          <span class="text-gray-700">Comprehensive warranty options</span>
                        </li>
                        <li class="flex items-start">
                          <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                          <span class="text-gray-700">Trade-in evaluations and assistance</span>
                        </li>
                        <li class="flex items-start">
                          <svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                          <span class="text-gray-700">After-sales support and maintenance</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

                <!-- Call to Action -->
                <div class="bg-blue-50 p-8 rounded-lg text-center">
                  <h2 class="text-2xl font-bold text-gray-900 mb-4">Ready to Find Your Dream Car?</h2>
                  <p class="text-gray-600 mb-6">
                    Browse our extensive inventory or schedule a consultation with our expert team.
                  </p>
                  <div class="space-x-4">
                    <Link
                      :href="route('automobiles.index')"
                      class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors"
                    >
                      Browse Inventory
                    </Link>
                    <Link
                      :href="route('contact')"
                      class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3 px-6 rounded-lg transition-colors"
                    >
                      Contact Us
                    </Link>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue'
import AppLayout from '@/Components/Layout/AppLayout.vue'

export default {
  name: 'AboutPage',
  components: {
    Head,
    Link,
    AppLayout
  }
}
</script>
