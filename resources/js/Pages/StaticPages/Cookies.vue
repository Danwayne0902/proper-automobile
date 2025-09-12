<template>
  <div>
    <Head title="Cookie Policy - Proper Automobile" />

    <AppLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Cookie Policy
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
            <div class="prose prose-lg max-w-none">
              <h1 class="text-3xl font-bold text-gray-900 mb-6">Cookie Policy</h1>
              <p class="text-sm text-gray-500 mb-8">Last updated: {{ currentDate }}</p>

              <div class="space-y-8">
                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">What Are Cookies</h2>
                  <p class="text-gray-600 mb-4">
                    Cookies are small text files that are stored on your computer or mobile device when you visit a website.
                    They help websites remember your preferences and improve your browsing experience.
                  </p>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">How We Use Cookies</h2>
                  <p class="text-gray-600 mb-4">
                    We use cookies to enhance your experience on our website, remember your preferences,
                    and provide personalized content and advertisements.
                  </p>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Types of Cookies We Use</h2>
                  <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Essential cookies: Required for the website to function properly</li>
                    <li>Performance cookies: Help us understand how visitors interact with our website</li>
                    <li>Functionality cookies: Remember your preferences and settings</li>
                    <li>Marketing cookies: Used to deliver personalized advertisements</li>
                  </ul>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Managing Cookies</h2>
                  <p class="text-gray-600 mb-4">
                    You can control and manage cookies through your browser settings. Please note that
                    disabling certain cookies may affect the functionality of our website.
                  </p>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Us</h2>
                  <p class="text-gray-600">
                    If you have any questions about our use of cookies, please contact us at
                    <a href="mailto:privacy@properautomobile.com" class="text-blue-600 hover:text-blue-800">
                      privacy@properautomobile.com
                    </a>
                  </p>
                </section>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue'
import AppLayout from '@/Components/Layout/AppLayout.vue'

export default {
  name: 'CookiesPage',
  components: {
    Head,
    AppLayout
  },
  computed: {
    currentDate() {
      return new Date().toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    }
  }
}
</script>
