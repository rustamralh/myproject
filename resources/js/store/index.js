import { createStore } from 'vuex';
import AuthModules from './modules/AuthModules.js';
const store = createStore({
    modules: {
        AuthModules
    }
});
export default store;