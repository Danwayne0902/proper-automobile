<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          User Management
        </h2>
        <Link
          :href="route('admin.users.create')"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          Add New User
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Filters -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 p-6">
          <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Search Users</label>
              <input
                v-model="searchForm.search"
                type="text"
                placeholder="Name or email..."
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
              <select
                v-model="searchForm.role"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
                <option value="">All Roles</option>
                <option v-for="role in roles" :key="role" :value="role">
                  {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                </option>
              </select>
            </div>
            <div class="flex items-end space-x-2">
              <button
                type="submit"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
              >
                Filter
              </button>
              <button
                type="button"
                @click="clearFilters"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
              >
                Clear
              </button>
            </div>
          </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    User
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Role
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Activity
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Joined
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                          <span class="text-sm font-medium text-gray-700">
                            {{ user.name.charAt(0).toUpperCase() }}
                          </span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                        <div class="text-sm text-gray-500">{{ user.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="getRoleBadgeClass(user.role)"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    >
                      {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="space-y-1">
                      <div v-if="user.automobiles_count">{{ user.automobiles_count }} automobiles</div>
                      <div v-if="user.bookings_count">{{ user.bookings_count }} bookings</div>
                      <div v-if="user.transactions_count">{{ user.transactions_count }} transactions</div>
                      <div v-if="!user.automobiles_count && !user.bookings_count && !user.transactions_count" class="text-gray-400">
                        No activity
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(user.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <Link
                      :href="route('admin.users.show', user.id)"
                      class="text-indigo-600 hover:text-indigo-900"
                    >
                      View
                    </Link>
                    <Link
                      :href="route('admin.users.edit', user.id)"
                      class="text-yellow-600 hover:text-yellow-900"
                    >
                      Edit
                    </Link>
                    <button
                      v-if="user.id !== $page.props.auth.user.id"
                      @click="deleteUser(user)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="users.links.length > 3" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
              <Link
                v-if="users.prev_page_url"
                :href="users.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Previous
              </Link>
              <Link
                v-if="users.next_page_url"
                :href="users.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Next
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
                </p>
              </div>
              <div class="flex space-x-1">
                <template v-for="(link, index) in users.links">
                  <Link
                    v-if="link.url"
                    :key="'link-' + index"
                    :href="link.url"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                      link.active
                        ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                    ]"
                  >
                    <span v-html="link.label" />
                  </Link>
                  <span
                    v-else
                    :key="'span-' + index"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                      'bg-white border-gray-300 text-gray-500 cursor-not-allowed'
                    ]"
                    v-html="link.label"
                  />
                </template>
              </div>
            </div>
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
  name: 'AdminUsersIndex',
  components: {
    AppLayout,
    Link
  },
  props: {
    users: {
      type: Object,
      required: true
    },
    filters: {
      type: [Object, Array],
      default: () => ({})
    },
    roles: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      searchForm: {
        search: this.filters.search || '',
        role: this.filters.role || ''
      }
    }
  },
  methods: {
    applyFilters() {
      Inertia.get(route('admin.users.index'), this.searchForm, {
        preserveState: true,
        replace: true
      })
    },
    clearFilters() {
      this.searchForm = {
        search: '',
        role: ''
      }
      this.applyFilters()
    },
    getRoleBadgeClass(role) {
      switch (role) {
        case 'admin':
          return 'bg-red-100 text-red-800'
        case 'dealer':
          return 'bg-blue-100 text-blue-800'
        case 'customer':
          return 'bg-green-100 text-green-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    },
    async deleteUser(user) {
      const result = await this.$swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to delete ${user.name}? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      })

      if (result.isConfirmed) {
        Inertia.delete(route('admin.users.destroy', user.id), {
          onSuccess: () => {
            this.$swal.fire(
              'Deleted!',
              `${user.name} has been deleted successfully.`,
              'success'
            )
          }
        })
      }
    }
  }
}
</script>
