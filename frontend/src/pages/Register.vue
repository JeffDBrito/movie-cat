<script setup>

import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { auth } from '../stores/auth'
import MovieCatLogo from '../components/MovieCatLogo.vue'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')

const error = ref(null)
const router = useRouter()
const authStore = auth()

const handleRegister = async () => {

    error.value = null

    if (password.value !== password_confirmation.value) {
        error.value = "As senhas não coincidem"
        return
    }
    if (password.value.length < 6) {
        error.value = "A senha deve ter pelo menos 6 caracteres"
        return
    }
    if (!email.value.includes('@')) {
        error.value = "Email inválido"
        return
    }
    if (name.value.length < 3) {
        error.value = "O nome deve ter pelo menos 3 caracteres"
        return
    }

    try {
        await axios.get('/sanctum/csrf-cookie')
        .catch((e) => {
            error.value = 'Erro ao obter o cookie CSRF. Verifique as configurações de ambiente ou sua base de dados.'
            return e.response
        })

        if (error.value) {
            return
        }

        const response = await axios.post('/api/user/store', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value
        }, {
            withCredentials: true
        }).then((response) => {
            router.push('/login')
        }).catch((e) => {
            console.error("Register Error: ", e)
            error.value = e.response.data.message
            return e.response
        })


    } catch (err) {
        error.value = err.message
    }
}

</script>
<template>

    <div class="register-container lg:mt-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center justify-center">
            <div class="col-span-6 content-center m-auto mr-10 px-10 lg:border-r">
                <div class="mb-5">
                    <MovieCatLogo cor="branca" tamanho="1/2" />
                </div>
                <div class="text-center hidden md:hidden lg:block">
                    <h2 class="text-2xl font-bold mb-4 mx-auto">Sistema de Gerenciamento de Filmes</h2>
                </div>
                <div class="mt-10 hidden sm:hidden md:hidden lg:block">
                    <p class="text-lg text-left mb-4 mx-auto">
                        Seja bem-vindo(a) ao nosso sistema de gerenciamento de filmes! 
                        <br>
                        Aqui você pode explorar uma vasta coleção de filmes e gerenciar sua lista pessoal com os seus filmes favoritos.
                        <br><br>
                    </p> 
                    <p class="text-lg font-bold mb-4 mx-auto">
                        Registre-se para começar a sua jornada cinematográfica!
                    </p>
                </div>
            </div>
            <div class="col-span-6 p-8 rounded shadow bg-white text-black px-10 shadow-sm shadow-black">
                <h2 class="text-2xl font-bold mb-4 mx-auto">Cadastro</h2>
                <p v-if="error" class="error">{{ error }}</p>
                <form @submit.prevent="handleRegister" class="grid grid-cols-1 gap-4 text-left">
                    <div class="grid grid-cols-1 text-black">
                        <label class="font-bold m-0 p-0" for="email">Seu nome:</label>
                        <input class="border rounded px-2 p-2 border border-purple-400 bg-white" v-model="name" type="text" placeholder="Seu nome"
                            required />
                    </div>
                    <div class="grid grid-cols-1 text-black">
                        <label class="font-bold m-0 p-0" for="email">Email:</label>
                        <input class="border rounded px-2 p-2 border border-purple-400 bg-white" v-model="email" type="email" placeholder="Email"
                            required />
                    </div>
                    <div class="grid grid-cols-1 text-black">
                        <label class="font-bold m-0 p-0" for="password">Senha:</label>
                        <input class="border rounded px-2 p-2 border border-purple-400 bg-white" v-model="password" type="password"
                            placeholder="Senha" required />
                    </div>
                    <div class="grid grid-cols-1 text-black">
                        <label class="font-bold m-0 p-0" for="password">Confirmar Senha:</label>
                        <input class="border rounded px-2 p-2 border border-purple-400 bg-white" v-model="password_confirmation" type="password" placeholder="Confirmar Senha" required />
                    </div>
                    <button class="text-white bg-purple-700" type="submit">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>

</template>


<style scoped>
.error {
    color: red;
}
</style>