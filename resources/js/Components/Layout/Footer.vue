<template>
  <footer class="bg-gray-800 text-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Company Info -->
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center">
            <img :src="logoUrl" alt="Proper Automobile" class="h-8 w-8 object-contain">
            <span class="ml-2 text-xl font-bold">Proper Automobile</span>
          </div>
          <p class="mt-4 text-gray-300 max-w-md">
            Your trusted partner in finding the perfect vehicle. We connect dealers and customers
            with a seamless car buying experience.
          </p>
          <div class="mt-6 flex space-x-6">
            <!-- Social Links -->
            <a href="#" class="text-gray-400 hover:text-white">
              <span class="sr-only">Facebook</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white">
              <span class="sr-only">Twitter</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-white">
              <span class="sr-only">LinkedIn</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M19 0H5a5 5 0 00-5 5v14a5 5 0 005 5h14a5 5 0 005-5V5a5 5 0 00-5-5zM8 19H5V8h3v11zM6.5 6.732c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zM20 19h-3v-5.604c0-3.368-4-3.113-4 0V19h-3V8h3v1.765c1.396-2.586 7-2.777 7 2.476V19z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Quick Links</h3>
          <ul class="mt-4 space-y-4">
            <li>
              <Link :href="route('automobiles.index')" class="text-base text-gray-300 hover:text-white">
                Browse Cars
              </Link>
            </li>
            <li>
              <Link :href="route('about')" class="text-base text-gray-300 hover:text-white">
                About Us
              </Link>
            </li>
            <li>
              <Link :href="route('contact')" class="text-base text-gray-300 hover:text-white">
                Contact
              </Link>
            </li>
            <li>
              <Link :href="route('faq')" class="text-base text-gray-300 hover:text-white">
                FAQ
              </Link>
            </li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
          <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Contact Info</h3>
          <ul class="mt-4 space-y-4">
            <li class="flex items-center">
              <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <span class="text-base text-gray-300">+1 (555) 123-4567</span>
            </li>
            <li class="flex items-center">
              <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <a href="mailto:info@properautomobile.com" class="text-base text-gray-300 hover:text-white">
                info@properautomobile.com
              </a>
            </li>
            <li class="flex items-start">
              <svg class="h-5 w-5 text-gray-400 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span class="text-base text-gray-300">
                123 Auto Street<br>
                Car City, CC 12345
              </span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom Section -->
      <div class="mt-12 border-t border-gray-700 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="flex space-x-6">
            <Link :href="route('privacy')" class="text-sm text-gray-400 hover:text-white">
              Privacy Policy
            </Link>
            <Link :href="route('terms')" class="text-sm text-gray-400 hover:text-white">
              Terms of Service
            </Link>
            <Link :href="route('cookies')" class="text-sm text-gray-400 hover:text-white">
              Cookie Policy
            </Link>
          </div>
          <div class="mt-4 md:mt-0">
            <p class="text-sm text-gray-400">
              &copy; {{ currentYear }} Proper Automobile. All rights reserved.
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'

export default {
  name: 'Footer',
  components: {
    Link
  },
  data() {
    return {
      logoUrl: '/proper-autos.png'
    }
  },
  computed: {
    currentYear() {
      return new Date().getFullYear()
    }
  }
}
</script>
