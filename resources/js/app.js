// Import all setup related js files
import './setup/index.js';

// Added: Actual Bootstrap JavaScript dependency
import 'bootstrap';

// Added: Popper.js dependency for popover support in Bootstrap
import '@popperjs/core';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import { createRouter, createWebHistory } from 'vue-router';

// Added loading overlay plugin
import { LoadingPlugin } from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

// Import your routes
import routes from './routes';

const router = createRouter({
    history: createWebHistory(),
    routes, // pass the routes to the router
    scrollBehavior: (to, from, savedPosition) => {
        return {
            x: 0,
            y: 0,
            behavior: 'smooth'
        }
    }
});

createApp(App)
    .use(router)
    .use(LoadingPlugin)
    .use(createPinia())
    .mount('#app');
