import './bootstrap';
import { createRouter, createWebHistory } from 'vue-router';
import { createApp } from 'vue'
import store from './store/index.js';
import App from './pages/App.vue'
import Login from './pages/Auth/Login.vue'
import Register from './pages/Auth/Register.vue'
import Dashboard from './pages/Dashboard.vue'
import Photos from './pages/Photos.vue'
import ExampleComponenent from './components/ExampleComponenent.vue'
import router from './router';

// router.beforeEach(function (to, from, next) {
//     window.scrollTo(0, 0);
//     next();
// });

const app = createApp(App)
app.use(router);
app.use(store);
app.mount('#app');