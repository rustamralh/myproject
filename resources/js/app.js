import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { ZiggyVue } from "ziggy";
import { Inertia } from "@inertiajs/inertia";
import store from "./store/index.js";
import "primeflex/primeflex.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

import PrimeVue from "primevue/config";
import Button from "primevue/button";
import FullPageLayout from "./pages/FullPageLayout.vue";
createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./pages/**/*.vue", { eager: true });
        return pages[`./pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .component("FullPageLayout", FullPageLayout)
            .use(plugin)
            .use(PrimeVue)
            // .component("Button", Button)
            .use(Inertia)
            .use(ZiggyVue, Ziggy)
            .use(store)
            .mount(el);
    },
});

// const app = createApp()
// app.use(store);
// app.mount('#app');
