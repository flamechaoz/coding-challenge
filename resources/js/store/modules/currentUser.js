
const state = {

    user_data: {
        logged_in: false,
        token: "",
        expires_at: ""
    },

    authUser: {}
};

const getters = {

    getAuthUser: state => state.authUser,
    getUserData: state => state.user_data

};

const actions = {

};

const mutations = {

    setUserData(state, data) {
        // Vue.set(state.user_data , data);
        state.user_data = data;
    },

    setAuthUser(state, data) {
        // Vue.set(state.authUser, data);
        state.authUser = data;
    },

};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}