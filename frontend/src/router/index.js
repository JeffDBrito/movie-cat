import { createRouter, createWebHistory } from 'vue-router'
import { auth } from '../stores/auth';

import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import HelloWorld from '../pages/Home.vue'

import Filmes from '../pages/Filmes.vue'
import MeusFilmes from '../pages/MeusFilmes.vue'

const routes = [
    
    // Home route
    { path: '/', component: HelloWorld, name: 'home' },
    { path: '/:pathMatch(.*)*', redirect: '/' }, // Redireciona para a página inicial se a rota não for encontrada

    // Authentication routes
    { path: '/login', component: Login, name: 'login' },
    { path: '/register', component: Register, name: 'register' },

    // Filmes routes
    { path: '/filmes', component: Filmes, name: 'filmes' },
    { path: '/meus-filmes', component: MeusFilmes, name: 'meus-filmes' },

]

export const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    linkActiveClass: 'active',
    routes
});

router.beforeEach(async (to, from) => {
    // Redireciona para a página de login se o usuário não estiver autenticado
    const publicPages = [
        '/',
        '/login',
        '/register',
        '/:pathMatch(.*)*', // Pega todas as rotas não definidas
        '/filmes'
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