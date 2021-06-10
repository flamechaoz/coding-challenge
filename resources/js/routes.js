import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import Axios from 'axios';

Vue.use(VueRouter)

const routes = [
    //
    {
        path: '/',
        component: require('./components/Auth/Login.vue').default,
        name: 'index',
        meta: {
            title: 'Watch | Login'
        }

    },
    {

        path: '/login',
        component: require('./components/Auth/Login.vue').default,
        name: 'login',
        meta: {
            title: 'Watch | Login'
        }
    },
    {

        path: '/dashboard',
        component: require('./components/Dashboard/Dashboard.vue').default,
        name: 'dashboard',
        meta: {
            title: 'Watch | Dashboard',
            requiresAuth: true
        }
    },
    {

        path: '/home',
        component: require('./components/Dashboard/Dashboard.vue').default,
        name: 'home',
        meta: {
            title: 'Watch | Dashboard',
            requiresAuth: true
        }
    },
    {

        path: '/students',
        component: require('./components/Student/Student.vue').default,
        name: 'home',
        meta: {
            title: 'Watch | Students',
            requiresAuth: true
        }
    },
    {
        path: '*',
        component: function (resolve) {
            require(['./components/404.vue'], resolve);
        }
    }

]

const router = new VueRouter({
    mode: 'hash',
    routes: routes
});

//VUEX CONFIG
const reset_user_session = () => {

    store.state.currentUser.user_data = {
        logged_in: false,
        token: "",
        expires_at: ""
    };

    localStorage.removeItem("_watch_user");

};

router.beforeEach((to, from, next) => {
    document.title = typeof to.meta.title === 'undefined' ? 'Watch' : to.meta.title;
    let local_storage_log = false

    if (localStorage.getItem("_watch_user")) {
        local_storage_log = JSON.parse(window.atob(window.atob(localStorage.getItem("_watch_user")).split("-")[1]));
    }

    if (local_storage_log && store.state.currentUser.user_data.logged_in === false) {
        store.commit("currentUser/setUserData", local_storage_log);
    }

    if (to.name === 'notfound') {
        next();
    } else if (to.matched.some(record => record.meta.requiresAuth)) {

        if (!(local_storage_log && store.state.currentUser.user_data.logged_in)) {
            //requires login

            //Resetting vuex
            reset_user_session();

            next({
                path: '/login'
            })

        } else {

            //continue  to private pages

            const AuthStr = JSON.parse(
                window.atob(window.atob(localStorage.getItem("_watch_user")).split("-")[1])
            ).token;

            axios.get("/api/auth/userData", {
                headers: {
                    Authorization: AuthStr
                }
            })
                .catch(err => {
                    reset_user_session();

                    next({
                        path: '/login',
                    });
                })
                .then(data => {

                    if (data.status !== 200) {

                        reset_user_session();

                        next({
                            path: '/login',
                        })

                    } else {
                        store.commit("currentUser/setAuthUser", data.data)
                        next();
                    }
                });
        }

    } else {
        if (local_storage_log && store.state.currentUser.user_data.logged_in) {

            const AuthStr = JSON.parse(
                window.atob(window.atob(localStorage.getItem("_watch_user")).split("-")[1])
            ).token;

            axios.get("/api/auth/userData",{
                headers: {
                    Authorization: AuthStr
                }
            })
                .catch(err => {
                    reset_user_session();

                    next();
                })
                .then(data => {
                    if (data.status !== 200) {

                        reset_user_session();

                        next()

                    } else {

                        store.commit("currentUser/setAuthUser", data.data)
                        next({
                            path: '/home',
                        });
                    }
                });
        } else {

            //Resetting vuex
            reset_user_session();

            next();
        }
    }
});

export default router;