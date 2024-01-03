import Vue from "vue";
import Vuex from "vuex";
import HttpConfig from "@/config/HttpConfig";

Vue.use(Vuex)

export default new Vuex.Store({
    getters: {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
    },
    state: {
        status: '',
        token: sessionStorage.getItem('token_user') || '',
        user: JSON.parse(sessionStorage.getItem('user')) || {},
        ruta: sessionStorage.getItem('ruta') || '',
    },
    mutations: {
        auth_request(state) {
            state.status = 'loading'
        },
        auth_success(state, { token, user, ruta }) {
            state.status = 'success'
            state.token = token
            state.user = user
            state.ruta = ruta
        },
        auth_error(state) {
            state.status = 'error'
        },
        logout(state) {
            state.status = ''
            state.token = ''
            state.ruta = ''
        }
    },
    actions: {
        login({ commit }, usuario) {
            let token = usuario.token;
            const user = usuario.usuario;
            const ruta = usuario.ruta;

            Vue.prototype.$http = HttpConfig(token);
            sessionStorage.setItem('token_user', token);
            sessionStorage.setItem('user', JSON.stringify(user));
            sessionStorage.setItem('ruta', ruta);
            commit('auth_success', { token, user, ruta });
        },
        logout({ commit }) {
            return new Promise((resolve, reject) => {
                commit('logout')
                sessionStorage.removeItem('token_user');
                sessionStorage.removeItem('user');
                resolve();
            });
        }
    },
    modules: {}
})