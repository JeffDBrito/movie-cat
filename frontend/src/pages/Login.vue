<script setup>

  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import axios from 'axios'
  import { auth } from '../stores/auth'


  const email = ref('')
  const password = ref('')
  const error = ref(null)
  const router = useRouter()
  const authStore = auth()

  const handleLogin = async () => {
      
        await axios.get('/sanctum/csrf-cookie')
        await axios.post('/api/login', {
            email: email.value,
            password: password.value
        },{
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

    <div class="login-container">
        <div class="grid grid-cols-12 items-center justify-center">
            <div class="col-span-6 content-center m-auto mr-10 px-10 border-r">
                <div class="mb-5">
                    <img src="../assets/logo_branca.png" alt="Logo" class="w-1/2 py-3 mx-auto mb-2" />
                </div>
                <div class="text-center">
                    <h2 class="text-2xl font-bold mb-4 mx-auto">Sistema de Gerenciamento de Filmes</h2>
                </div>
                <div class="mt-10">
                    <p class="text-lg text-left mb-4 mx-auto">
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
            <div class="col-span-6 p-8 rounded shadow bg-white text-black login-conntent shadow-sm shadow-black">
                <h2 class="text-2xl font-bold mb-4 mx-auto">Login</h2>
                <p v-if="error" class="error">{{ error }}</p>
                <form @submit.prevent="handleLogin" class="grid grid-cols-1 gap-4 text-left">
                    <div class="grid grid-cols-1 text-black">
                    <label class="font-bold m-0 p-0" for="email">Email:</label>
                    <input class="border rounded px-2 p-2 border border-purple-400 bg-white" v-model="email" type="email" placeholder="Email" required />
                    </div>
                    <div class="grid grid-cols-1 text-black">
                    <label class="font-bold m-0 p-0" for="password">Senha:</label>
                    <input class="border rounded px-2 p-2 border border-purple-400 bg-white" v-model="password" type="password" placeholder="Senha" required />            
                    </div>
                    <button class="text-white bg-purple-600" type="submit">Entrar</button>
                </form>
            </div>
        </div>
    </div>

</template>

  
<style scoped>
  .login-container {
    margin-top: 10%;
  }

.login-conntent {
    min-width: 500px;
    padding-inline: 30px;
}

.logo{
    background-color: #ededed;
    border-radius: 100%;
    padding-block: 6%;
}

.error {
    color: red;
}
</style>
  