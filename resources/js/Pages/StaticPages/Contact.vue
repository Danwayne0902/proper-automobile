<template>
    <div>

        <Head title="Contact Us - Proper Automobile" />

        <AppLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Contact Us
                </h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8">
                            <div class="max-w-4xl mx-auto text-center">
                                <h1 class="text-4xl font-bold mb-4">Get in Touch</h1>
                                <p class="text-xl text-blue-100">
                                    We're here to help you find your perfect vehicle
                                </p>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="max-w-6xl mx-auto">
                                <div class="grid lg:grid-cols-2 gap-12">

                                    <!-- Contact Form -->
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>

                                        <!-- Success Message -->
                                        <div v-if="$page.props.flash.success"
                                            class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                                            <div class="flex">
                                                <svg class="flex-shrink-0 h-5 w-5 text-green-400 mt-0.5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-green-800">{{
                                                        $page.props.flash.success }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Error Message -->
                                        <div v-if="$page.props.flash.error"
                                            class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                                            <div class="flex">
                                                <svg class="flex-shrink-0 h-5 w-5 text-red-400 mt-0.5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-red-800">{{
                                                        $page.props.flash.error }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <form @submit.prevent="submitForm" class="space-y-6">
                                            <div class="grid md:grid-cols-2 gap-6">
                                                <div>
                                                    <label for="first_name"
                                                        class="block text-sm font-medium text-gray-700 mb-2">
                                                        First Name *
                                                    </label>
                                                    <input id="first_name" v-model="form.first_name" type="text"
                                                        required
                                                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                        :class="{ 'border-red-300': $page.props.errors.first_name, 'border-gray-300': !$page.props.errors.first_name }" />
                                                    <p v-if="$page.props.errors.first_name"
                                                        class="mt-1 text-sm text-red-600">{{
                                                        $page.props.errors.first_name }}</p>
                                                </div>
                                                <div>
                                                    <label for="last_name"
                                                        class="block text-sm font-medium text-gray-700 mb-2">
                                                        Last Name *
                                                    </label>
                                                    <input id="last_name" v-model="form.last_name" type="text" required
                                                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                        :class="{ 'border-red-300': $page.props.errors.last_name, 'border-gray-300': !$page.props.errors.last_name }" />
                                                    <p v-if="$page.props.errors.last_name"
                                                        class="mt-1 text-sm text-red-600">{{
                                                        $page.props.errors.last_name }}</p>
                                                </div>
                                            </div>

                                            <div>
                                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Email Address *
                                                </label>
                                                <input id="email" v-model="form.email" type="email" required
                                                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    :class="{ 'border-red-300': $page.props.errors.email, 'border-gray-300': !$page.props.errors.email }" />
                                                <p v-if="$page.props.errors.email" class="mt-1 text-sm text-red-600">{{
                                                    $page.props.errors.email }}</p>
                                            </div>

                                            <div>
                                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Phone Number
                                                </label>
                                                <input id="phone" v-model="form.phone" type="tel"
                                                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    :class="{ 'border-red-300': $page.props.errors.phone, 'border-gray-300': !$page.props.errors.phone }" />
                                                <p v-if="$page.props.errors.phone" class="mt-1 text-sm text-red-600">{{
                                                    $page.props.errors.phone }}</p>
                                            </div>

                                            <div>
                                                <label for="subject"
                                                    class="block text-sm font-medium text-gray-700 mb-2">
                                                    Subject *
                                                </label>
                                                <select id="subject" v-model="form.subject" required
                                                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    :class="{ 'border-red-300': $page.props.errors.subject, 'border-gray-300': !$page.props.errors.subject }">
                                                    <option value="">Select a subject</option>
                                                    <option value="inquiry">General Inquiry</option>
                                                    <option value="test_drive">Schedule Test Drive</option>
                                                    <option value="financing">Financing Options</option>
                                                    <option value="trade_in">Trade-in Evaluation</option>
                                                    <option value="service">Service & Support</option>
                                                    <option value="other">Other</option>
                                                </select>
                                                <p v-if="$page.props.errors.subject" class="mt-1 text-sm text-red-600">
                                                    {{ $page.props.errors.subject }}</p>
                                            </div>

                                            <div>
                                                <label for="message"
                                                    class="block text-sm font-medium text-gray-700 mb-2">
                                                    Message *
                                                </label>
                                                <textarea id="message" v-model="form.message" rows="6" required
                                                    placeholder="Please provide details about your inquiry..."
                                                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    :class="{ 'border-red-300': $page.props.errors.message, 'border-gray-300': !$page.props.errors.message }"></textarea>
                                                <p v-if="$page.props.errors.message" class="mt-1 text-sm text-red-600">
                                                    {{ $page.props.errors.message }}</p>
                                            </div>

                                            <button type="submit" :disabled="processing"
                                                class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center">
                                                <svg v-if="processing"
                                                    class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                {{ processing ? 'Sending...' : 'Send Message' }}
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Contact Information -->
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h2>

                                        <div class="space-y-8">
                                            <!-- Address -->
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-6 h-6 text-blue-600 mt-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <h3 class="text-lg font-semibold text-gray-900">Visit Our Showroom
                                                    </h3>
                                                    <p class="text-gray-600 mt-1">
                                                        123 Luxury Auto Drive<br />
                                                        Premium District<br />
                                                        Car City, CC 12345
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Phone -->
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-6 h-6 text-blue-600 mt-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <h3 class="text-lg font-semibold text-gray-900">Call Us</h3>
                                                    <p class="text-gray-600 mt-1">
                                                        Sales: +234 (705) 340-4846<br />
                                                        Service: +234 (703) 948-1762<br />
                                                        Mon-Fri: 9 AM - 8 PM<br />
                                                        Sat-Sun: 10 AM - 6 PM
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-6 h-6 text-blue-600 mt-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <h3 class="text-lg font-semibold text-gray-900">Email Us</h3>
                                                    <p class="text-gray-600 mt-1">
                                                        <a href="mailto:danwayne0902@gmail.com"
                                                            class="hover:text-blue-600">
                                                            info@properautomobile.com
                                                        </a><br />
                                                        <a href="mailto:danwayne0902@yahoo.com"
                                                            class="hover:text-blue-600">
                                                            sales@properautomobile.com
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quick Actions -->
                                        <div class="mt-12">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                                            <div class="space-y-3">
                                                <Link :href="route('automobiles.index')"
                                                    class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-4 rounded-lg transition-colors">
                                                Browse Our Inventory
                                                </Link>
                                                <button
                                                    class="block w-full text-center bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold py-3 px-4 rounded-lg transition-colors">
                                                    Schedule Test Drive
                                                </button>
                                                <button
                                                    class="block w-full text-center bg-green-100 hover:bg-green-200 text-green-800 font-semibold py-3 px-4 rounded-lg transition-colors">
                                                    Get Trade-in Value
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Map Section -->
                                        <div class="mt-12">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Find Us</h3>
                                            <div class="h-64 rounded-lg overflow-hidden">
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
            </div>
        </AppLayout>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue'
import { Inertia } from '@inertiajs/inertia'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import MapComponent from '@/Components/MapComponent.vue'

export default {
  name: 'ContactPage',
  components: {
    Head,
    Link,
    AppLayout,
    MapComponent
  },
  data() {
    return {
      processing: false,
      form: {
        first_name: this.$page.props.old?.first_name || '',
        last_name: this.$page.props.old?.last_name || '',
        email: this.$page.props.old?.email || '',
        phone: this.$page.props.old?.phone || '',
        subject: this.$page.props.old?.subject || '',
        message: this.$page.props.old?.message || ''
      }
    }
  },
  methods: {
    submitForm() {
      this.processing = true

      Inertia.post(route('contact.store'), this.form, {
        onSuccess: () => {
          this.processing = false
          // Reset form on success
          this.form = {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            subject: '',
            message: ''
          }
        },
        onError: () => {
          this.processing = false
        },
        onFinish: () => {
          this.processing = false
        }
      })
    }
  }
}
</script>
