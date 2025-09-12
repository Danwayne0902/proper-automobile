<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-primary leading-tight">
        System Settings
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Settings Navigation -->
        <div class="bg-primary overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="border-b border-primary">
            <nav class="-mb-px flex space-x-8 px-6">
              <button
                v-for="tab in tabs"
                :key="tab.key"
                @click="activeTab = tab.key"
                :class="[
                  activeTab === tab.key
                    ? 'border-accent text-accent'
                    : 'border-transparent text-secondary hover:text-primary hover:border-tertiary',
                  'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
                ]"
              >
                {{ tab.name }}
              </button>
            </nav>
          </div>
        </div>

        <!-- General Settings -->
        <div v-if="activeTab === 'general'" class="bg-primary overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h3 class="text-lg font-medium text-primary mb-6">General Settings</h3>
          <form @submit.prevent="updateSettings('general')" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-primary">Application Name</label>
                <input
                  v-model="generalForm.app_name"
                  type="text"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-primary">Contact Email</label>
                <input
                  v-model="generalForm.contact_email"
                  type="email"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-primary">Contact Phone</label>
                <input
                  v-model="generalForm.contact_phone"
                  type="text"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-primary">Business Hours</label>
                <input
                  v-model="generalForm.business_hours"
                  type="text"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                >
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-primary">Business Address</label>
                <textarea
                  v-model="generalForm.address"
                  rows="3"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                ></textarea>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-primary">Description</label>
                <textarea
                  v-model="generalForm.app_description"
                  rows="3"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                ></textarea>
              </div>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="clearCache"
                class="bg-warning hover:bg-opacity-90 text-white font-bold py-2 px-4 rounded"
              >
                Clear Cache
              </button>
              <button
                type="submit"
                class="bg-accent hover:bg-opacity-90 text-white font-bold py-2 px-4 rounded"
              >
                Save General Settings
              </button>
            </div>
          </form>
        </div>

        <!-- Storage Settings -->
        <div v-if="activeTab === 'storage'" class="bg-primary overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h3 class="text-lg font-medium text-primary mb-6">Storage Settings</h3>

          <!-- Storage Usage -->
          <div class="mb-8">
            <h4 class="text-md font-medium text-primary mb-4">Storage Usage</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-secondary p-4 rounded-lg">
                <dt class="text-sm font-medium text-secondary">Public Storage Used</dt>
                <dd class="text-lg font-medium text-primary">
                  {{ settings.storage.public_disk_usage.used_mb }} MB
                </dd>
              </div>
              <div class="bg-secondary p-4 rounded-lg">
                <dt class="text-sm font-medium text-secondary">Total Images</dt>
                <dd class="text-lg font-medium text-primary">{{ settings.storage.total_images }}</dd>
              </div>
              <div class="bg-secondary p-4 rounded-lg">
                <dt class="text-sm font-medium text-secondary">Storage Limit</dt>
                <dd class="text-lg font-medium text-primary">{{ settings.storage.storage_limit_gb }} GB</dd>
              </div>
            </div>
          </div>

          <form @submit.prevent="updateSettings('storage')" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="flex items-center">
                  <input
                    v-model="storageForm.auto_cleanup_enabled"
                    type="checkbox"
                    class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                  >
                  <span class="ml-2 text-sm text-primary">Enable Auto Cleanup</span>
                </label>
              </div>
              <div>
                <label class="block text-sm font-medium text-primary">Cleanup After (Days)</label>
                <input
                  v-model.number="storageForm.cleanup_days"
                  type="number"
                  min="30"
                  max="365"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                >
              </div>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                class="bg-accent hover:bg-opacity-90 text-white font-bold py-2 px-4 rounded"
              >
                Save Storage Settings
              </button>
            </div>
          </form>
        </div>

        <!-- Security Settings -->
        <div v-if="activeTab === 'security'" class="bg-primary overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h3 class="text-lg font-medium text-primary mb-6">Security Settings</h3>
          <form @submit.prevent="updateSettings('security')" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-primary">Minimum Password Length</label>
                <input
                  v-model.number="securityForm.password_min_length"
                  type="number"
                  min="6"
                  max="20"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-primary">Max Login Attempts</label>
                <input
                  v-model.number="securityForm.max_login_attempts"
                  type="number"
                  min="3"
                  max="10"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-primary">Lockout Duration (Minutes)</label>
                <input
                  v-model.number="securityForm.lockout_duration"
                  type="number"
                  min="5"
                  max="60"
                  class="mt-1 block w-full bg-primary border border-primary rounded-md shadow-sm focus:ring-accent focus:border-accent text-primary"
                >
              </div>
            </div>

            <div class="space-y-4">
              <div class="flex items-center">
                <input
                  v-model="securityForm.require_email_verification"
                  type="checkbox"
                  class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                >
                <label class="ml-2 text-sm text-primary">Require Email Verification</label>
              </div>
              <div class="flex items-center">
                <input
                  v-model="securityForm.two_factor_enabled"
                  type="checkbox"
                  class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                >
                <label class="ml-2 text-sm text-primary">Enable Two-Factor Authentication</label>
              </div>
              <div class="flex items-center">
                <input
                  v-model="securityForm.audit_logs_enabled"
                  type="checkbox"
                  class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                >
                <label class="ml-2 text-sm text-primary">Enable Audit Logs</label>
              </div>
            </div>

            <div class="flex justify-end">
              <button
                type="submit"
                class="bg-accent hover:bg-opacity-90 text-white font-bold py-2 px-4 rounded"
              >
                Save Security Settings
              </button>
            </div>
          </form>
        </div>

        <!-- Features Settings -->
        <div v-if="activeTab === 'features'" class="bg-primary overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h3 class="text-lg font-medium text-primary mb-6">Feature Settings</h3>
          <form @submit.prevent="updateSettings('features')" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <h4 class="font-medium text-primary">User Features</h4>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      v-model="featuresForm.user_registration"
                      type="checkbox"
                      class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                    >
                    <span class="ml-2 text-sm text-primary">User Registration</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="featuresForm.guest_browsing"
                      type="checkbox"
                      class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                    >
                    <span class="ml-2 text-sm text-primary">Guest Browsing</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="featuresForm.test_drive_booking"
                      type="checkbox"
                      class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                    >
                    <span class="ml-2 text-sm text-primary">Test Drive Booking</span>
                  </label>
                </div>
              </div>

              <div class="space-y-4">
                <h4 class="font-medium text-primary">System Features</h4>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      v-model="featuresForm.online_payments"
                      type="checkbox"
                      class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                    >
                    <span class="ml-2 text-sm text-primary">Online Payments</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="featuresForm.automated_emails"
                      type="checkbox"
                      class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                    >
                    <span class="ml-2 text-sm text-primary">Automated Emails</span>
                  </label>
                  <label class="flex items-center">
                    <input
                      v-model="featuresForm.analytics_tracking"
                      type="checkbox"
                      class="rounded border-primary text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 bg-primary"
                    >
                    <span class="ml-2 text-sm text-primary">Analytics Tracking</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="flex justify-end">
              <button
                type="submit"
                class="bg-accent hover:bg-opacity-90 text-white font-bold py-2 px-4 rounded"
              >
                Save Feature Settings
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from '@/Components/Layout/AppLayout.vue'
import { Inertia } from '@inertiajs/inertia'

export default {
  name: 'AdminSettingsIndex',
  components: {
    AppLayout
  },
  props: {
    settings: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      activeTab: 'general',
      tabs: [
        { key: 'general', name: 'General' },
        { key: 'storage', name: 'Storage' },
        { key: 'security', name: 'Security' },
        { key: 'features', name: 'Features' }
      ],
      generalForm: { ...this.settings.general },
      storageForm: { ...this.settings.storage },
      securityForm: { ...this.settings.security },
      featuresForm: { ...this.settings.features }
    }
  },
  methods: {
    updateSettings(category) {
      let formData
      switch (category) {
        case 'general':
          formData = this.generalForm
          break
        case 'storage':
          formData = this.storageForm
          break
        case 'security':
          formData = this.securityForm
          break
        case 'features':
          formData = this.featuresForm
          break
      }

      Inertia.patch(route('admin.settings.update'), {
        ...formData,
        category: category
      })
    },
    clearCache() {
      if (confirm('Are you sure you want to clear the application cache?')) {
        Inertia.post(route('admin.settings.clear-cache'))
      }
    }
  }
}
</script>
