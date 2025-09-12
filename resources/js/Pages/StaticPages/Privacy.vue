<template>
  <div>
    <Head title="Privacy Policy - Proper Automobile" />

    <AppLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Privacy Policy
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
            <div class="prose prose-lg max-w-none">
              <h1 class="text-3xl font-bold text-gray-900 mb-6">Privacy Policy</h1>
              <p class="text-sm text-gray-500 mb-8">Last updated: {{ currentDate }}</p>

              <div class="space-y-8">
                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Information We Collect</h2>
                  <p class="text-gray-600 mb-4">
                    We collect information you provide directly to us, such as when you create an account,
                    schedule a test drive, request information about our vehicles, or contact us for support.
                  </p>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">How We Use Your Information</h2>
                  <p class="text-gray-600 mb-4">
                    We use the information we collect to provide, maintain, and improve our services,
                    process transactions, and communicate with you about our products and services.
                  </p>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Information Sharing</h2>
                  <p class="text-gray-600 mb-4">
                    We do not sell, trade, or otherwise transfer your personal information to third parties
                    without your consent, except as described in this privacy policy.
                  </p>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Us</h2>
                  <p class="text-gray-600">
                    If you have any questions about this Privacy Policy, please contact us at
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
  name: 'PrivacyPage',
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
