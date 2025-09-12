<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Edit User
        </h2>
        <Link
          :href="route('admin.users.index')"
          class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
        >
          Back to Users
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                  <input
                    v-model="form.name"
                    type="text"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.name }"
                  >
                  <div v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</div>
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input
                    v-model="form.email"
                    type="email"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.email }"
                  >
                  <div v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</div>
                </div>

                <!-- Role -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                  <select
                    v-model="form.role"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.role }"
                  >
                    <option value="">Select Role</option>
                    <option v-for="role in roles" :key="role" :value="role">
                      {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                    </option>
                  </select>
                  <div v-if="errors.role" class="text-red-500 text-sm mt-1">{{ errors.role }}</div>
                </div>

                <!-- Phone -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                  <input
                    v-model="form.phone"
                    type="text"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.phone }"
                  >
                  <div v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone }}</div>
                </div>

                <!-- Password -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                  <input
                    v-model="form.password"
                    type="password"
                    placeholder="Leave blank to keep current password"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.password }"
                  >
                  <div v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</div>
                </div>

                <!-- Password Confirmation -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                  <input
                    v-model="form.password_confirmation"
                    type="password"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.password_confirmation }"
                  >
                  <div v-if="errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ errors.password_confirmation }}</div>
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                  <textarea
                    v-model="form.address"
                    rows="3"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': errors.address }"
                  ></textarea>
                  <div v-if="errors.address" class="text-red-500 text-sm mt-1">{{ errors.address }}</div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end mt-6">
                <button
                  type="submit"
                  :disabled="processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                  :class="{ 'opacity-75 cursor-not-allowed': processing }"
                >
                  <span v-if="processing">Updating...</span>
                  <span v-else>Update User</span>
                </button>
              </div>
            </form>
          </div>
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
  name: 'AdminUsersEdit',
  components: {
    AppLayout,
    Link
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    roles: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      processing: false,
      errors: {},
      form: {
        name: this.user.name,
        email: this.user.email,
        role: this.user.role,
        phone: this.user.phone || '',
        address: this.user.address || '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  methods: {
    submit() {
      this.processing = true
      this.errors = {}

      Inertia.put(route('admin.users.update', this.user.id), this.form, {
        onSuccess: () => {
          this.processing = false
        },
        onError: (errors) => {
          this.processing = false
          this.errors = errors
        }
      })
    }
  }
}
</script>
