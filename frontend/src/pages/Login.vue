<template>

    <div class="login-container">
      <div class="flex items-center justify-center">
        <div class="p-8 rounded shadow">
          <h2 class="text-2xl font-bold mb-4 mx-auto">Login</h2>
          <form @submit.prevent="handleLogin" class="grid grid-cols-1 gap-4">
            <input v-model="email" type="email" placeholder="Email" required />
            <input v-model="password" type="password" placeholder="Senha" required />
            <button type="submit">Entrar</button>
          </form>
        </div>
      </div>
      <p v-if="error" class="error">{{ error }}</p>
    </div>

</template>
  
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
    try {
        axios.get('/sanctum/csrf-cookie').then(() => {console.log("CSRF Cookie set")})
        const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value
        },{
            withCredentials: true
        }).then((response) => {
            console.log("Login Response: ",response.data)
            authStore.setUser(response.data.user)
            authStore.setToken(response.data.token)

            authStore.getAuthUser()

            router.push('/home')
        }).catch((error) => {
            console.error("Login Error: ",error)
            return error.response
        })


    } catch (err) {
        error.value = err.message
    }
}

</script>

  
  <style scoped>
  .login-container {
    /* max-width: 300px; */
    /* margin: 0 auto; */
  }
  .error {
    color: red;
  }
</style>
  