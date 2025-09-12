<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Payment Accounts
        </h2>
        <div class="text-sm text-gray-500">
          Manage your payment accounts for receiving payments
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
              <h3 class="text-lg font-medium text-gray-900">Your Payment Accounts</h3>
              <p class="mt-1 text-sm text-gray-500">
                Add and manage payment accounts where payments will be received.
              </p>
            </div>
            <Link
              :href="route('bank-accounts.create')"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
              Add Payment Account
            </Link>
          </div>

          <div class="p-6">
            <!-- Payment Accounts Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Account
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Payment Method
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Details
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="bankAccount in bankAccounts" :key="bankAccount.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ bankAccount.account_name }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ bankAccount.currency }} - {{ bankAccount.country_code }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ getPaymentMethodLabel(bankAccount.payment_method) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="bankAccount.payment_method === 'bank_transfer' || bankAccount.payment_method === 'ach' || bankAccount.payment_method === 'sepa' || bankAccount.payment_method === 'swift'">
                        <div class="text-sm font-medium text-gray-900">
                          {{ bankAccount.bank_name }}
                        </div>
                        <div class="text-sm text-gray-500">
                          ****{{ bankAccount.account_number.slice(-4) }}
                        </div>
                        <div class="text-sm text-gray-500" v-if="bankAccount.routing_number">
                          Routing: {{ bankAccount.routing_number }}
                        </div>
                      </div>
                      <div v-else>
                        <div class="text-sm text-gray-500">
                          ****{{ bankAccount.account_number.slice(-4) }}
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="bankAccount.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      >
                        {{ bankAccount.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <Link
                          :href="route('bank-accounts.edit', bankAccount.id)"
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          Edit
                        </Link>
                        <button
                          @click="deleteBankAccount(bankAccount)"
                          class="text-red-600 hover:text-red-900"
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Empty State -->
                  <tr v-if="bankAccounts.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No payment accounts found.
                      <Link
                        :href="route('bank-accounts.create')"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        Add your first payment account
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from '@/Components/Layout/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue'
import { Inertia } from '@inertiajs/inertia'
import Swal from 'sweetalert2'

export default {
  name: 'BankAccountsIndex',

  components: {
    AppLayout,
    Link
  },

  props: {
    bankAccounts: {
      type: Array,
      required: true
    }
  },

  methods: {
    getPaymentMethodLabel(method) {
      const paymentMethods = {
        'bank_transfer': 'Bank Transfer',
        'paypal': 'PayPal',
        'stripe': 'Stripe',
        'wire_transfer': 'Wire Transfer',
        'ach': 'ACH Transfer',
        'sepa': 'SEPA Transfer',
        'swift': 'SWIFT Transfer'
      };
      return paymentMethods[method] || method;
    },

    async deleteBankAccount(bankAccount) {
      const result = await Swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to delete the payment account ending in ${bankAccount.account_number.slice(-4)}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      })

      if (result.isConfirmed) {
        Inertia.delete(route('bank-accounts.destroy', bankAccount.id), {
          onSuccess: () => {
            Swal.fire(
              'Deleted!',
              'Your payment account has been deleted.',
              'success'
            )
          }
        })
      }
    }
  }
}
</script>
