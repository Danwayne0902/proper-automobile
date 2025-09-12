import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress';
import Vue from 'vue';
import Swal from 'sweetalert2';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

Vue.config.productionTip = false;

// Make SweetAlert2 globally available
Vue.prototype.$swal = Swal;

// Wait for window.route to be available and then set it up
const setupRoute = () => {
    if (typeof window.route !== 'undefined') {
        Vue.prototype.$route = window.route;

        // Add global mixin for route helper
        Vue.mixin({
            methods: {
                route: window.route
            }
        });
    } else {
        // If route is not available, provide a fallback
        const routeFallback = (name, params) => {
            console.warn(`route('${name}') called but window.route is not available`);
            return '#';
        };

        Vue.prototype.$route = routeFallback;
        Vue.mixin({
            methods: {
                route: routeFallback
            }
        });
    }
};

// Set up route helper
setupRoute();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        Vue.use(plugin);

        new Vue({
            render: (h) => h(App, props),
        }).$mount(el);
    },
});

InertiaProgress.init({
  color: '#3B82F6',
  showSpinner: true,
  delay: 250,
  includeCSS: true,
  spinnerColor: '#3B82F6'
});
