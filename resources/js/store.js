import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

/**
 * Global Store
 */
export default new Vuex.Store({
    plugins: [createPersistedState({key: 'laravue_session'})],
    state: {
        token: null,
        user: null,
        loading: false,
        permissions: null,
        hasDrawerLeft: false,
        hasDrawerRight: false,
    },
    mutations: {
        setToken(state, payload) {
            state.token = payload.token;
        },
        setUser(state, payload) {
            state.user = payload.user;
        },
        setIsLoading(state, payload) {
            state.loading = payload.loading;
        },
        setPermissions(state, payload) {
            state.permissions = payload.permissions;
        },
        setHasDrawerLeft(state, payload) {
            state.hasDrawerLeft = payload.hasDrawerLeft;
        },
        setHasDrawerRight(state, payload) {
            state.hasDrawerRight = payload.hasDrawerRight;
        },
        clearAll(state) {
            state.token = null;
            state.user = null;
            state.permissions = null;
            state.hasDrawerLeft = false;
            state.hasDrawerRight = false;
        },
    },
    actions: {},
    getters: {
        isLoading: (state) => {
            return state.loading;
        },
        getToken: (state) => {
            return state.token;
        },
        getUser: (state) => {
            return state.user;
        },
        getPermissions: (state) => {
            return state.permissions;
        },
        getHasDrawerLeft: (state) => {
            return state.hasDrawerLeft;
        },
        getHasDrawerRight: (state) => {
            return state.hasDrawerRight;
        },
    },
});
