<script>
import { Link } from '@inertiajs/inertia-vue';
import { Inertia } from '@inertiajs/inertia';
import Navbar from './Navbar.vue';
import Footer from './Footer.vue';

export default {
    name: 'PublicLayout',
    components: {
        Link,
        Navbar,
        Footer
    },
    data() {
        return {
            logoUrl: '/proper-autos.png'
        }
    },
    methods: {
        goToHome() {
            Inertia.visit('/');
        }
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 @click="goToHome" class="text-2xl font-bold text-gray-900 flex items-center cursor-pointer">
                                <img :src="logoUrl" alt="Proper Automobile" class="h-8 w-8 object-contain mr-2">
                                <span class="text-blue-600">Proper</span> Automobile
                            </h1>
                        </div>
                    </div>

                    <div class="hidden md:flex md:items-center md:space-x-4">
                        <Link
                            :href="'/'"
                            class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors"
                        >
                            Home
                        </Link>

                        <Link
                            :href="route('automobiles.index')"
                            class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors"
                        >
                            Browse Cars
                        </Link>

                        <template v-if="$page.props.auth && $page.props.auth.user">
                            <Link
                                :href="route('dashboard')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors"
                            >
                                Login
                            </Link>
                            <Link
                                :href="route('register')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Register
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>
