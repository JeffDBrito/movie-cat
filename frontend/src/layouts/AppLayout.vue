<script setup>

import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { auth } from '../stores/auth'
import axios from 'axios'

const router = useRouter()
const authStore = auth()

onMounted( async () => {

    // Verifica se o cookie de sessão do Laravel está presente
    if (document.cookie.includes('laravel_session')) {
        // Se o cookie estiver presente, tenta obter o usuário autenticado
        try {
            const { data } = await axios.get('/user', { withCredentials: true })
            authStore.setUser(data)
            console.log('Usuário autenticado:', data)
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
    if (!authStore.isAuthenticated()) {
        router.push(router.resolve({ name: 'login' }))
    }
})


</script>

<template>
    <div class="layout text-center">
      <header class="grid grid-cols-12 gap-4"> 

        <!-- Home/Logo -->
        <div class="col-span-3">
            <div class="w-1/4 content-center m-auto">
                <router-link to="/" class="m-auto">
                    <img src="../assets/logo_branca.png" alt="Logo" class="mx-auto py-3" />
                </router-link>
            </div>
        </div>

        <!-- Menu -->
        <div class="col-span-6 content-center">
            <nav class="flex justify-center space-x-4" v-if="authStore.isAuthenticated()">
                <router-link to="/">Home</router-link>
                <router-link to="/">Login</router-link>
                <router-link to="/">Registrar</router-link>
                <router-link to="/">Filmes</router-link>
            </nav>
        </div>

        <!-- Login/Logout -->
        <div class="col-span-3 content-center">
            <div class="flex justify-center">
                <router-link class="justify-center bg-white text-white rounded-lg px-2" to="/" v-if="!authStore.isAuthenticated()">Login</router-link>
                <router-link class="justify-center bg-white text-white rounded-lg px-2" to="/" v-else @click="authStore.logout()">Logout</router-link>
            </div>
        </div>

      </header>
  
      <main>
        <slot />
      </main>
  
      <footer>
        <p>Catálogo de filmes - Desenvolvido por <a href="https://www.linkedin.com/in/jefferson-brito-a462b117a/" target="_blank">Jefferson Brito</a></p>
      </footer>
    </div>
  </template>
  
  <style scoped>
  .layout {
    padding: 20px;
    /* max-width: 800px; */
    margin-inline: 0 auto;
  }
  header{
    position: fixed;
    left: 0;
    top: 0;
    background-color: #36013d;
    width: 100%;  
  }
  footer {
    font-size: 14px;
    padding: 10px;
    text-align: center;
    position: fixed;
    left: 0;
    bottom: 0;
    background-color: #36013d;
    width: 100%;  
  }
  </style>
  