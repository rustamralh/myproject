import { createStore } from 'vuex';
import AuthModules from './modules/AuthModules.js';
const store = createStore({
    modules: {
        auth: AuthModules
    }
});
export default store;