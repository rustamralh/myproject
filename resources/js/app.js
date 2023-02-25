import './bootstrap';
import { createRouter, createWebHistory } from 'vue-router';
import { createApp } from 'vue'
import App from './pages/App.vue'
import Login from './pages/Auth/Login.vue'
import Register from './pages/Auth/Register.vue'
import ExampleComponenent from './components/ExampleComponenent.vue'
import router from './router';

// router.beforeEach(function (to, from, next) {
//     window.scrollTo(0, 0);
//     next();
// });

const app = createApp(App)
app.use(router);
app.mount('#app');