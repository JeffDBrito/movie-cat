<script setup>

import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { auth } from '../stores/auth'

import MovieCatLogo from '../components/MovieCatLogo.vue'

const email = ref('')
const password = ref('')
const error = ref(null)
const router = useRouter()
const authStore = auth()

const handleLogin = async () => {

    error.value = null

    await axios.get('/sanctum/csrf-cookie')
    .catch((e) => {
        error.value = 'Erro ao obter o cookie CSRF. Verifique as configurações de ambiente ou sua base de dados.'
        return e.response
    })

    if (error.value) {
        return
    }
    await axios.post('/api/login', {
        email: email.value,
        password: password.value
    }, {
        withCredentials: true
    }).then((response) => {
        authStore.setUser(response.data.user)
        authStore.setToken(response.data.token)

        authStore.getAuthUser()

        router.push('/')
    }).catch((e) => {
        error.value = e.response.data.message
        return e.response
    })

}

</script>
<template>

    <div class="login-container lg:mt-20">
        <div class="grid sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-12 items-center justify-center md:shrink-0">
            <div class="lg:col-span-6 content-center m-auto mr-10 px-10 lg:border-r">
                <div class="mb-5">
                     <MovieCatLogo cor="branca" tamanho="1/2" />
                </div>
                <div class="text-center sm:mb-10 hidden md:hidden lg:block">
                    <h2 class="text-2xl font-bold mb-4 mx-auto">Sistema de Gerenciamento de Filmes</h2>
                </div>
                <div class="mt-10 hidden sm:hidden md:hidden lg:block">
                    <p class="text-lg md:text-left mb-4 mx-auto">
                        Seja bem-vindo(a) de volta ao seu sistema de gerenciamento de filmes favorito!
                        <br>
                        Gerencie a sua coleção de filmes favoritos e descubra novos títulos incríveis.
                        <br><br>
                    </p>
                    <p class="text-lg font-bold mb-4 mx-auto">
                        Faça login para continuar explorando o mundo do cinema!
                    </p>
                </div>
            </div>
            <div class="lg:col-span-6 p-8 rounded shadow bg-white text-black px-10 shadow-sm shadow-black">
                <h2 class="text-2xl font-bold mb-4 mx-auto">Login</h2>
                <p v-if="error" class="error">{{ error }}</p>
                <form @submit.prevent="handleLogin" class="grid grid-cols-1 gap-4 text-left">
                    <div class="grid grid-cols-1 text-black">
                        <label class="font-bold m-0 p-0" for="email">Email:</label>
                        <input class="border rounded px-2 p-2 border border-purple-400 bg-white" v-model="email"
                            type="email" placeholder="Email" required />
                    </div>
                    <div class="grid grid-cols-1 text-black">
                        <label class="font-bold m-0 p-0" for="password">Senha:</label>
                        <input class="border rounded px-2 p-2 border border-purple-400 bg-white" v-model="password" type="password" placeholder="Senha" required />
                    </div>
                    <div>
                        <p class="text-sm text-left mb-4 mx-auto">
                            Esqueceu sua senha? <router-link to="/forgot-password" class="text-purple-600">Recuperar</router-link>
                        </p>
                    </div>
                    <button class="text-white bg-purple-600" type="submit">Entrar</button>
                    <div class="grid grid-cols-1 text-black">
                        <p class="text-sm text-left mb-4 mx-auto">
                            Não tem uma conta? <router-link to="/register" class="text-purple-600">Cadastre-se</router-link>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>


<style scoped>

.logo {
    background-color: #ededed;
    border-radius: 100%;
    padding-block: 6%;
}

.error {
    color: red;
}
</style>