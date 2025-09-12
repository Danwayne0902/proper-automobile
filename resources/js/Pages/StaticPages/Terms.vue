<template>
  <div>
    <Head title="Terms of Service - Proper Automobile" />

    <AppLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Terms of Service
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
            <div class="prose prose-lg max-w-none">
              <h1 class="text-3xl font-bold text-gray-900 mb-6">Terms of Service</h1>
              <p class="text-sm text-gray-500 mb-8">Last updated: {{ currentDate }}</p>

              <div class="space-y-8">
                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Acceptance of Terms</h2>
                  <p class="text-gray-600 mb-4">
                    By accessing and using this website, you accept and agree to be bound by the terms
                    and provision of this agreement.
                  </p>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Use License</h2>
                  <p class="text-gray-600 mb-4">
                    Permission is granted to temporarily download one copy of the materials on Proper Automobile's
                    website for personal, non-commercial transitory viewing only.
                  </p>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Disclaimer</h2>
                  <p class="text-gray-600 mb-4">
                    The materials on Proper Automobile's website are provided on an 'as is' basis.
                    Proper Automobile makes no warranties, expressed or implied.
                  </p>
                </section>

                <section>
                  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Information</h2>
                  <p class="text-gray-600">
                    If you have any questions about these Terms of Service, please contact us at
                    <a href="mailto:legal@properautomobile.com" class="text-blue-600 hover:text-blue-800">
                      legal@properautomobile.com
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
  name: 'TermsPage',
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
