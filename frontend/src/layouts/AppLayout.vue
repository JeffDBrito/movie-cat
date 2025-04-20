<script setup>

import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { auth } from '../stores/auth'
import MovieCatLogo from '../components/MovieCatLogo.vue'
import axios from 'axios'

const router = useRouter()
const authStore = auth()

const showBurguerMenu = ref(false)
const toggleBurguerMenu = () => {
    showBurguerMenu.value = !showBurguerMenu.value
}

onMounted(async () => {

    // Verifica se o cookie de sessão do Laravel está presente
    if (document.cookie.includes('laravel_session')) {
        // Se o cookie estiver presente, tenta obter o usuário autenticado
        try {
            const { data } = await axios.get('/user', { withCredentials: true })
            authStore.setUser(data)
        } catch (error) {
            // Se ocorrer um erro, verifica se o status é 401 (não autorizado) e, se for o caso, limpa o usuário do authStore
            if (error.response?.status === 401) {
                authStore.setUser(null)
            } else {
                console.error("Erro inesperado ao obter usuário:", error)
            }
        }
    }

    // Verifica se o usuário está autenticado e redireciona para a página de login se não estiver
    // if (!authStore.isAuthenticated()) {
    //     router.push(router.resolve({ name: 'login' }))
    // }
})

</script>

<template>
    <div class="layout text-center">


        <!-- Mobile Header -->
        <header class="grid grid-cols-12 gap-4 md:hidden">
            <!-- Home/Logo -->
            <div class="col-span-3 col-span-3 content-center">
                <movie-cat-logo cor="branca" :home="true" tamanho="w-100"></movie-cat-logo>
            </div>

            <!-- Burguer button -->

            <div class="col-span-9 content-center pr-5">
                <div class="flex justify-end">
                    <button class="text-white hover:bg-white hover:text-purple-800 rounded-lg px-3"
                        @click="toggleBurguerMenu()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6h15M4.5 12h15m-15 6h15" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Login/Logout  -->
            <div v-if="showBurguerMenu"  class="col-span-12 content-center">
                <div class="flex justify-center mb-5">
                    <router-link class="px-3 rounded hover:bg-white hover:text-purple-800 " to="/">Home</router-link>
                    <router-link class="px-3 rounded hover:bg-white hover:text-purple-800 " to="/filmes">Filmes</router-link>
                    <router-link class="px-3 rounded hover:bg-white hover:text-purple-800 " to="/meus-filmes" v-if="authStore.isAuthenticated()">Meus Filmes</router-link>
                    <router-link class="justify-center text-white hover:bg-white hover:text-purple-800 rounded-lg px-3" to="/login" v-if="!authStore.isAuthenticated()">Login</router-link>
                    <router-link class="justify-center text-white hover:bg-white hover:text-purple-800 rounded-lg px-3" to="/register" v-if="!authStore.isAuthenticated()">Cadastre-se</router-link>
                    <router-link class="justify-center text-white hover:bg-red-400 hover:text-white rounded-lg px-3" to="/" v-else @click="authStore.logout()">Logout</router-link>
                </div>
            </div>
        </header>

        <!-- PC Header -->
        <header class="grid grid-cols-12 gap-4 hidden md:grid">

            <!-- Home/Logo -->
            <div class="col-span-3">
                <div class="w-1/4 content-center m-auto">
                    <movie-cat-logo cor="branca" :home="true" tamanho="w-100"></movie-cat-logo>
                </div>
            </div>

            <!-- Menu -->
            <div class="col-span-6 content-center">
                <nav class="flex justify-left space-x-4 text-white">
                    <router-link class="px-3 rounded hover:bg-white hover:text-purple-800 " to="/">Home</router-link>
                    <router-link class="px-3 rounded hover:bg-white hover:text-purple-800 " to="/filmes">Filmes</router-link>
                    <router-link class="px-3 rounded hover:bg-white hover:text-purple-800 " to="/meus-filmes" v-if="authStore.isAuthenticated()">Meus Filmes</router-link>
                </nav>
            </div>

            <!-- Login/Logout -->
            <div class="col-span-3 content-center">
                <div class="flex justify-center">
                    <router-link class="justify-center text-white hover:bg-white hover:text-purple-800 rounded-lg px-3"
                        to="/login" v-if="!authStore.isAuthenticated()">Login</router-link>
                    <router-link class="justify-center text-white hover:bg-white hover:text-purple-800 rounded-lg px-3"
                        to="/register" v-if="!authStore.isAuthenticated()">Cadastre-se</router-link>
                    <router-link class="justify-center text-white hover:bg-red-400 hover:text-white rounded-lg px-3"
                        to="/" v-else @click="authStore.logout()">Logout</router-link>
                </div>
            </div>

        </header>

        <main id="app-content">
            <slot />
        </main>

        <footer class="fixed bottom-0 w-full py-1">
            <p>Catálogo de filmes - Desenvolvido por <a href="https://www.linkedin.com/in/jefferson-brito-a462b117a/" target="_blank">Jefferson Brito</a></p>
        </footer>
    </div>
</template>

<style scoped>
.layout {
    margin-inline: 0 auto;
}

header {
    left: 0;
    top: 0;
    background-color: #0000002e;
    width: 100%;
}

footer {
    font-size: 14px;
    background-color: #0000002e;
}
</style>