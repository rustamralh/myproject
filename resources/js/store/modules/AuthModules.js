export default {
    namespaced: true,
    state() {
        return {
            userDetails: {
                userName: 'sahil',
                userPassword: '123456'
            },
            isAuthenticated: false
        }
    },
    mutations: {
        SET_AUTHENTICATION(state, value) {
            state.isAuthenticated = value
            console.log(value, state.isAuthenticated)
        }
    },
    actions: {
        isAuth(context, payload) {
            const currenUserDetails = context.state.userDetails
            if (payload.name === currenUserDetails.userName && payload.password === currenUserDetails.userPassword) {
                context.commit('SET_AUTHENTICATION', true)
            } else {
                context.commit('SET_AUTHENTICATION', false)
            }
            console.log(currenUserDetails, payload)
        }
    },
    getters: {
        userDetails(state) {
            return state.userDetails
        },
        userAuthentication(state) {
            return state.isAuthenticated
        }
    }
}