import { createRouter, createWebHistory } from 'vue-router'
import Login from '../pages/Login.vue'
import HelloWorld from '../pages/HelloWorld.vue'
import { auth } from '../stores/auth';

const routes = [
    { path: '/', component: Login, name: 'login' },
    { path: '/home', component: HelloWorld, name: 'home' },
]

export const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    linkActiveClass: 'active',
    routes
});

router.beforeEach(async (to) => {
    // redirect to login page if not logged in and trying to access a restricted page
    const publicPages = ['/'];
    const authRequired = !publicPages.includes(to.path);
    const authInstance = auth();

    if (authRequired && !authInstance.user) {
        authInstance.returnUrl = to.fullPath;
        return '/';
    } else if (to.path === '/') {
        if (authInstance.user) {
            return '/home';
        }
    }
});