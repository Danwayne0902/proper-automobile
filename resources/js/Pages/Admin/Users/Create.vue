<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Create New User
        </h2>
        <Link
          :href="route('admin.users.index')"
          class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
        >
          Cancel
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="space-y-6">
              <!-- Basic Information -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Full Name <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.name"
                      type="text"
                      required
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      :class="{ 'border-red-300': errors.name }"
                    >
                    <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Email <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.email"
                      type="email"
                      required
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      :class="{ 'border-red-300': errors.email }"
                    >
                    <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Role <span class="text-red-500">*</span>
                    </label>
                    <select
                      v-model="form.role"
                      required
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      :class="{ 'border-red-300': errors.role }"
                    >
                      <option value="">Select Role</option>
                      <option v-for="role in roles" :key="role" :value="role">
                        {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                      </option>
                    </select>
                    <p v-if="errors.role" class="mt-1 text-sm text-red-600">{{ errors.role }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Phone Number
                    </label>
                    <input
                      v-model="form.phone"
                      type="tel"
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      :class="{ 'border-red-300': errors.phone }"
                    >
                    <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
                  </div>
                </div>
              </div>

              <!-- Password -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Security</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Password <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.password"
                      type="password"
                      required
                      minlength="8"
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      :class="{ 'border-red-300': errors.password }"
                    >
                    <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.password_confirmation"
                      type="password"
                      required
                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      :class="{ 'border-red-300': errors.password_confirmation }"
                    >
                    <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ errors.password_confirmation }}</p>
                  </div>
                </div>
              </div>

              <!-- Address -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Address
                  </label>
                  <textarea
                    v-model="form.address"
                    rows="3"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    :class="{ 'border-red-300': errors.address }"
                    placeholder="Enter full address..."
                  ></textarea>
                  <p v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address }}</p>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex justify-end space-x-3 pt-6">
                <Link
                  :href="route('admin.users.index')"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  {{ processing ? 'Creating...' : 'Create User' }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import { Inertia } from '@inertiajs/inertia'

export default {
  name: 'AdminUsersCreate',
  components: {
    AppLayout,
    Link
  },
  props: {
    roles: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      processing: false,
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: '',
        phone: '',
        address: ''
      },
      errors: {}
    }
  },
  methods: {
    submit() {
      this.processing = true
      this.errors = {}

      Inertia.post(route('admin.users.store'), this.form, {
        onSuccess: () => {
          this.processing = false
        },
        onError: (errors) => {
          this.errors = errors
          this.processing = false
        }
      })
    }
  }
}
</script>
