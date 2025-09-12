<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="updateProfile"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
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
import { Link } from '@inertiajs/inertia-vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: 'UpdateProfileInformationForm',
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
        Link
    },
    props: {
        user: {
            type: Object,
            default: null
        },
        mustVerifyEmail: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    },
    data: function() {
        return {
            form: {
                name: this.user ? this.user.name : '',
                email: this.user ? this.user.email : '',
                processing: false,
                errors: {},
                recentlySuccessful: false
            }
        }
    },
    methods: {
        updateProfile: function() {
            this.form.processing = true;
            this.form.errors = {};
            this.form.recentlySuccessful = false;

            Inertia.patch(route('profile.update'), {
                name: this.form.name,
                email: this.form.email
            }, {
                onSuccess: () => {
                    this.form.processing = false;
                    this.form.recentlySuccessful = true;
                    // Reset the recentlySuccessful flag after a delay
                    setTimeout(() => {
                        this.form.recentlySuccessful = false;
                    }, 2000);
                },
                onError: (errors) => {
                    this.form.processing = false;
                    this.form.errors = errors;
                }
            });
        }
    },
    watch: {
        user: {
            handler: function(newUser) {
                if (newUser) {
                    this.form.name = newUser.name;
                    this.form.email = newUser.email;
                }
            },
            immediate: true
        }
    }
}
</script>
