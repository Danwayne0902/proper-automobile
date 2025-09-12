<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Update Password
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" value="Current Password" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />

                <InputError
                    :message="form.errors.current_password"
                    class="mt-2"
                />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-2"
                />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: 'UpdatePasswordForm',
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput
    },
    data: function() {
        return {
            form: {
                current_password: '',
                password: '',
                password_confirmation: '',
                processing: false,
                errors: {},
                recentlySuccessful: false
            }
        }
    },
    methods: {
        updatePassword: function() {
            this.form.processing = true;
            this.form.errors = {};
            this.form.recentlySuccessful = false;

            Inertia.put(route('password.update'), {
                current_password: this.form.current_password,
                password: this.form.password,
                password_confirmation: this.form.password_confirmation
            }, {
                onSuccess: () => {
                    this.form.processing = false;
                    this.form.recentlySuccessful = true;
                    // Reset form on success
                    this.form.current_password = '';
                    this.form.password = '';
                    this.form.password_confirmation = '';
                    // Reset the recentlySuccessful flag after a delay
                    setTimeout(() => {
                        this.form.recentlySuccessful = false;
                    }, 2000);
                },
                onError: (errors) => {
                    this.form.processing = false;
                    this.form.errors = errors;
                    if (errors.password) {
                        this.form.password = '';
                        this.form.password_confirmation = '';
                        this.$refs.passwordInput.focus();
                    }
                    if (errors.current_password) {
                        this.form.current_password = '';
                        this.$refs.currentPasswordInput.focus();
                    }
                },
            });
        }
    }
}
</script>
