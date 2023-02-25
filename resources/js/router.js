import { createRouter, createWebHashHistory } from "vue-router";
import App from "./pages/App.vue";

const routes = [
    {
        path: "/",
        name: "dashboard",
        component: () => import("./pages/Dashboard.vue"),
    },
    {
        path: "/login",
        name: "login",
        component: () => import("./pages/Auth/Login.vue"),
    },
    {
        path: "/register",
        name: "register",
        component: () => import("./pages/Auth/Register.vue"),
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

export default router;
