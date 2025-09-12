<script>
import GlobalLoader from '@/Components/GlobalLoader.vue';
import { Link } from '@inertiajs/inertia-vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: 'GuestLayout',
    components: {
        GlobalLoader,
        Link
    },
    data() {
        return {
            loading: false,
            logoUrl: '/proper-autos.png'
        }
    },
    mounted() {
        // Set up Inertia loading events
        Inertia.on('start', () => {
            this.loading = true
        })

        Inertia.on('finish', () => {
            setTimeout(() => {
                this.loading = false
            }, 300)
        })
    }
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
        <!-- Global Loader -->
        <GlobalLoader :loading="loading" />

        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-100 rounded-full opacity-20 animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative min-h-screen flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
            <!-- Logo and Company Branding -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-block">
                    <div class="flex items-center justify-center mb-4">
                        <img :src="logoUrl" alt="Proper Automobile" class="h-16 w-16 object-contain mr-3">
                        <div class="text-left">
                            <h1 class="text-3xl font-bold text-gray-900">
                                <span class="text-blue-600">Proper</span> Automobile
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Premium Luxury Vehicles</p>
                        </div>
                    </div>
                </Link>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-blue-400 mx-auto rounded-full"></div>
            </div>

            <!-- Main Card -->
            <div class="w-full max-w-md">
                <div class="bg-white/80 backdrop-blur-sm shadow-2xl rounded-2xl border border-white/20 overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center justify-center">
                            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <h2 class="text-xl font-semibold text-white">Join Our Community</h2>
                        </div>
                        <p class="text-blue-100 text-sm mt-1 text-center">Start your luxury car journey today</p>
                    </div>

                    <!-- Card Body -->
                    <div class="px-6 py-6">
                        <slot />
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-6 text-center space-y-2">
                    <p class="text-sm text-gray-600">
                        By registering, you agree to our
                        <Link :href="route('terms')" class="text-blue-600 hover:text-blue-800 underline">Terms of Service</Link>
                        and
                        <Link :href="route('privacy')" class="text-blue-600 hover:text-blue-800 underline">Privacy Policy</Link>
                    </p>
                    <p class="text-xs text-gray-500">
                        &copy; 2024 Proper Automobile. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
