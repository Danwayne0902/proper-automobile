<template>
  <div>
    <Head title="FAQ - Proper Automobile" />

    <AppLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Frequently Asked Questions
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8">
              <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Frequently Asked Questions</h1>
                <p class="text-xl text-blue-100">
                  Find answers to common questions about our services
                </p>
              </div>
            </div>

            <div class="p-8">
              <div class="space-y-8">

                <!-- General Questions -->
                <div>
                  <h2 class="text-2xl font-bold text-gray-900 mb-6">General Questions</h2>
                  <div class="space-y-4">

                    <div class="border border-gray-200 rounded-lg">
                      <button
                        @click="toggleFaq('general-1')"
                        class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                      >
                        <div class="flex justify-between items-center">
                          <h3 class="text-lg font-semibold text-gray-900">
                            What types of vehicles do you sell?
                          </h3>
                          <svg
                            :class="{ 'rotate-180': openFaqs['general-1'] }"
                            class="w-5 h-5 text-gray-500 transform transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                        </div>
                      </button>
                      <div v-show="openFaqs['general-1']" class="p-4 pt-0 text-gray-600">
                        We specialize in luxury and premium vehicles from renowned manufacturers including Mercedes-Benz, BMW, Audi, Toyota, and many others. Our inventory includes sedans, SUVs, coupes, and convertibles, all carefully selected for quality and performance.
                      </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg">
                      <button
                        @click="toggleFaq('general-2')"
                        class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                      >
                        <div class="flex justify-between items-center">
                          <h3 class="text-lg font-semibold text-gray-900">
                            Do you offer financing options?
                          </h3>
                          <svg
                            :class="{ 'rotate-180': openFaqs['general-2'] }"
                            class="w-5 h-5 text-gray-500 transform transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                        </div>
                      </button>
                      <div v-show="openFaqs['general-2']" class="p-4 pt-0 text-gray-600">
                        Yes, we offer comprehensive financing solutions through our network of trusted lending partners. We can help you secure competitive rates and flexible terms that fit your budget. Our finance team will work with you to find the best option for your situation.
                      </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg">
                      <button
                        @click="toggleFaq('general-3')"
                        class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                      >
                        <div class="flex justify-between items-center">
                          <h3 class="text-lg font-semibold text-gray-900">
                            Can I schedule a test drive?
                          </h3>
                          <svg
                            :class="{ 'rotate-180': openFaqs['general-3'] }"
                            class="w-5 h-5 text-gray-500 transform transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                        </div>
                      </button>
                      <div v-show="openFaqs['general-3']" class="p-4 pt-0 text-gray-600">
                        Absolutely! We encourage test drives to ensure you're completely satisfied with your choice. You can schedule a test drive online through our website or by calling our showroom. We'll have the vehicle ready for you at your preferred time.
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Buying Process -->
                <div>
                  <h2 class="text-2xl font-bold text-gray-900 mb-6">Buying Process</h2>
                  <div class="space-y-4">

                    <div class="border border-gray-200 rounded-lg">
                      <button
                        @click="toggleFaq('buying-1')"
                        class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                      >
                        <div class="flex justify-between items-center">
                          <h3 class="text-lg font-semibold text-gray-900">
                            What documents do I need to purchase a vehicle?
                          </h3>
                          <svg
                            :class="{ 'rotate-180': openFaqs['buying-1'] }"
                            class="w-5 h-5 text-gray-500 transform transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                        </div>
                      </button>
                      <div v-show="openFaqs['buying-1']" class="p-4 pt-0 text-gray-600">
                        You'll need a valid driver's license, proof of insurance, and proof of income for financing. If you're trading in a vehicle, bring the title and registration. We'll help guide you through the entire documentation process.
                      </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg">
                      <button
                        @click="toggleFaq('buying-2')"
                        class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                      >
                        <div class="flex justify-between items-center">
                          <h3 class="text-lg font-semibold text-gray-900">
                            Do you accept trade-ins?
                          </h3>
                          <svg
                            :class="{ 'rotate-180': openFaqs['buying-2'] }"
                            class="w-5 h-5 text-gray-500 transform transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                        </div>
                      </button>
                      <div v-show="openFaqs['buying-2']" class="p-4 pt-0 text-gray-600">
                        Yes, we accept trade-ins and offer competitive evaluations. Our experienced appraisers will assess your vehicle's condition, mileage, and market value to provide you with a fair trade-in offer that can be applied toward your new purchase.
                      </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg">
                      <button
                        @click="toggleFaq('buying-3')"
                        class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                      >
                        <div class="flex justify-between items-center">
                          <h3 class="text-lg font-semibold text-gray-900">
                            What warranty options are available?
                          </h3>
                          <svg
                            :class="{ 'rotate-180': openFaqs['buying-3'] }"
                            class="w-5 h-5 text-gray-500 transform transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                        </div>
                      </button>
                      <div v-show="openFaqs['buying-3']" class="p-4 pt-0 text-gray-600">
                        We offer comprehensive warranty options including manufacturer warranties for new vehicles and extended warranty plans for pre-owned vehicles. Our service team can explain all available coverage options to ensure your peace of mind.
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Service & Support -->
                <div>
                  <h2 class="text-2xl font-bold text-gray-900 mb-6">Service & Support</h2>
                  <div class="space-y-4">

                    <div class="border border-gray-200 rounded-lg">
                      <button
                        @click="toggleFaq('service-1')"
                        class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                      >
                        <div class="flex justify-between items-center">
                          <h3 class="text-lg font-semibold text-gray-900">
                            Do you provide after-sales service?
                          </h3>
                          <svg
                            :class="{ 'rotate-180': openFaqs['service-1'] }"
                            class="w-5 h-5 text-gray-500 transform transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                        </div>
                      </button>
                      <div v-show="openFaqs['service-1']" class="p-4 pt-0 text-gray-600">
                        Yes, we provide comprehensive after-sales service including maintenance, repairs, and parts replacement. Our certified technicians are trained to service all the brands we sell, ensuring your vehicle receives expert care.
                      </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg">
                      <button
                        @click="toggleFaq('service-2')"
                        class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                      >
                        <div class="flex justify-between items-center">
                          <h3 class="text-lg font-semibold text-gray-900">
                            How can I contact customer support?
                          </h3>
                          <svg
                            :class="{ 'rotate-180': openFaqs['service-2'] }"
                            class="w-5 h-5 text-gray-500 transform transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                        </div>
                      </button>
                      <div v-show="openFaqs['service-2']" class="p-4 pt-0 text-gray-600">
                        You can reach our customer support team by phone at +234 (705) 340-4846, email us at info@properautomobile.com, or visit our showroom at 123 Luxury Auto Drive, Premium District, Car City, CC 12345. We're available Monday through Friday from 9 AM to 8 PM, and weekends from 10 AM to 6 PM.
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Contact Section -->
                <div class="bg-blue-50 p-6 rounded-lg">
                  <h2 class="text-xl font-bold text-gray-900 mb-4">Still Have Questions?</h2>
                  <p class="text-gray-600 mb-4">
                    Can't find the answer you're looking for? Our friendly customer service team is here to help.
                  </p>
                  <Link
                    :href="route('contact')"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors mb-6"
                  >
                    Contact Us
                  </Link>

                  <!-- Map Section -->
                  <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Visit Our Showroom</h3>
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
          </div>
        </div>
      </div>
    </AppLayout>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import MapComponent from '@/Components/MapComponent.vue'

export default {
  name: 'FAQPage',
  components: {
    Head,
    Link,
    AppLayout,
    MapComponent
  },
  data() {
    return {
      openFaqs: {}
    }
  },
  methods: {
    toggleFaq(faqId) {
      this.openFaqs = {
        ...this.openFaqs,
        [faqId]: !this.openFaqs[faqId]
      }
    }
  }
}
</script>
