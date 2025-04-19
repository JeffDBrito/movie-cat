import { createRouter, createWebHistory } from 'vue-router'
import { auth } from '../stores/auth';

import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import HelloWorld from '../pages/HelloWorld.vue'

const routes = [
    { path: '/login', component: Login, name: 'login' },
    { path: '/register', component: Register, name: 'register' },
    { path: '/', component: HelloWorld, name: 'home' },
]

export const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    linkActiveClass: 'active',
    routes
});

router.beforeEach(async (to, from) => {
    // redirect to login page if not logged in and trying to access a restricted page
    const publicPages = [
        '/',
        '/login',
        '/register',
    ];
    const authRequired = !publicPages.includes(to.path);
    const authInstance = auth();
    
    if (authRequired && !authInstance.user) {
        authInstance.returnUrl = to.fullPath;
        return '/login';
    } else if (to.path === '/login') {
        if (authInstance.user) {
            return '/';
        }
    }

});