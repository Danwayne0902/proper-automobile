<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Add Payment Account
        </h2>
        <div class="text-sm text-gray-500">
          Add a new payment account for receiving payments
        </div>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Payment Account Details</h3>
            <p class="mt-1 text-sm text-gray-500">
              Provide your payment account information where payments will be received.
            </p>
          </div>

          <div class="p-6">
            <form @submit.prevent="submitForm">
              <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Payment Method -->
                <div>
                  <InputLabel for="payment_method" value="Payment Method" />
                  <select
                    id="payment_method"
                    v-model="form.payment_method"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                    @change="onPaymentMethodChange"
                  >
                    <option v-for="(label, value) in $page.props.paymentMethods" :key="value" :value="value">
                      {{ label }}
                    </option>
                  </select>
                  <InputError :message="form.errors.payment_method" class="mt-2" />
                </div>

                <!-- Account Name -->
                <div>
                  <InputLabel for="account_name" value="Account Name" />
                  <TextInput
                    id="account_name"
                    v-model="form.account_name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                  />
                  <InputError :message="form.errors.account_name" class="mt-2" />
                </div>

                <!-- Account Number / Email / Identifier -->
                <div>
                  <InputLabel :for="'account_identifier'" :value="accountIdentifierLabel" />
                  <TextInput
                    :id="'account_identifier'"
                    v-model="form.account_number"
                    type="text"
                    class="mt-1 block w-full"
                    :placeholder="accountIdentifierPlaceholder"
                    required
                  />
                  <InputError :message="form.errors.account_number" class="mt-2" />
                </div>

                <!-- Conditional Fields based on Payment Method -->
                <template v-if="form.payment_method === 'bank_transfer' || form.payment_method === 'ach' || form.payment_method === 'sepa' || form.payment_method === 'swift'">
                  <!-- Bank Name -->
                  <div>
                    <InputLabel for="bank_name" value="Bank Name" />
                    <TextInput
                      id="bank_name"
                      v-model="form.bank_name"
                      type="text"
                      class="mt-1 block w-full"
                      required
                    />
                    <InputError :message="form.errors.bank_name" class="mt-2" />
                  </div>

                  <!-- Routing Number -->
                  <div>
                    <InputLabel for="routing_number" value="Routing/Sort Code" />
                    <TextInput
                      id="routing_number"
                      v-model="form.routing_number"
                      type="text"
                      class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.routing_number" class="mt-2" />
                  </div>

                  <!-- Account Type -->
                  <div>
                    <InputLabel for="account_type" value="Account Type" />
                    <select
                      id="account_type"
                      v-model="form.account_type"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      required
                    >
                      <option value="checking">Checking</option>
                      <option value="savings">Savings</option>
                    </select>
                    <InputError :message="form.errors.account_type" class="mt-2" />
                  </div>
                </template>

                <!-- Currency -->
                <div>
                  <InputLabel for="currency" value="Currency" />
                  <select
                    id="currency"
                    v-model="form.currency"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  >
                    <option v-for="(label, value) in $page.props.currencies" :key="value" :value="value">
                      {{ value }} - {{ label }}
                    </option>
                  </select>
                  <InputError :message="form.errors.currency" class="mt-2" />
                </div>

                <!-- Country Code -->
                <div>
                  <InputLabel for="country_code" value="Country" />
                  <select
                    id="country_code"
                    v-model="form.country_code"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  >
                    <option value="US">United States</option>
                    <option value="GB">United Kingdom</option>
                    <option value="DE">Germany</option>
                    <option value="FR">France</option>
                    <option value="JP">Japan</option>
                    <option value="CA">Canada</option>
                    <option value="AU">Australia</option>
                    <option value="CH">Switzerland</option>
                    <option value="CN">China</option>
                    <option value="IN">India</option>
                    <option value="BR">Brazil</option>
                    <option value="MX">Mexico</option>
                  </select>
                  <InputError :message="form.errors.country_code" class="mt-2" />
                </div>

                <!-- Status -->
                <div class="flex items-center mt-6">
                  <Checkbox v-model:checked="form.is_active" name="is_active" />
                  <InputLabel for="is_active" class="ml-2">Set as active account</InputLabel>
                </div>
              </div>

              <div class="flex items-center justify-end mt-6">
                <Link
                  :href="route('bank-accounts.index')"
                  class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-2"
                >
                  Cancel
                </Link>
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                  Save Payment Account
                </PrimaryButton>
              </div>
            </form>
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
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import Checkbox from '@/Components/Checkbox.vue'

export default {
  name: 'BankAccountsCreate',

  components: {
    AppLayout,
    Link,
    InputError,
    InputLabel,
    PrimaryButton,
    TextInput,
    Checkbox
  },

  data() {
    return {
      form: this.$inertia.form({
        payment_method: 'bank_transfer',
        account_name: '',
        account_number: '',
        bank_name: '',
        routing_number: '',
        account_type: 'checking',
        currency: 'USD',
        country_code: 'US',
        is_active: true,
        errors: {}
      })
    }
  },

  computed: {
    accountIdentifierLabel() {
      switch (this.form.payment_method) {
        case 'paypal':
          return 'PayPal Email';
        case 'stripe':
          return 'Stripe Account ID';
        default:
          return 'Account Number';
      }
    },
    accountIdentifierPlaceholder() {
      switch (this.form.payment_method) {
        case 'paypal':
          return 'user@example.com';
        case 'stripe':
          return 'acct_1234567890';
        default:
          return 'Account Number';
      }
    }
  },

  methods: {
    onPaymentMethodChange() {
      // Reset fields that are not relevant for the selected payment method
      if (this.form.payment_method !== 'bank_transfer' &&
          this.form.payment_method !== 'ach' &&
          this.form.payment_method !== 'sepa' &&
          this.form.payment_method !== 'swift') {
        this.form.bank_name = '';
        this.form.routing_number = '';
        this.form.account_type = 'checking';
      }
    },

    submitForm() {
      this.form.post(route('bank-accounts.store'), {
        onSuccess: () => {
          this.form.reset()
        },
        onError: (errors) => {
          this.form.errors = errors
        }
      })
    }
  }
}
</script>
