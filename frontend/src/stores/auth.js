import { ref, computed } from 'vue'
import axios from 'axios'
import { defineStore } from 'pinia'

export const auth = defineStore('auth', () => {
    const user = ref(null)
    const token = ref(null)
    const session = ref(null)
    const isLoggedIn = computed(() => !!user.value && !!token.value)

    function isAuthenticated() {
        return !!user.value && !!token.value
    }

    async function getAuthUser() {

        await axios.get('/sanctum/csrf-cookie', { withCredentials: true, })

        await axios.get('/user', { withCredentials: true, })
            .then((response) => {
                console.log('User data fetched successfully:', response.data)
                user.value = response.data
            })
            .catch((error) => {
                console.error('Error fetching user data:', error)
                user.value = null
            })

    }

    function login(userData, tokenData) {
        user.value = userData
        token.value = tokenData
    }

    function logout() {

        axios.post('/api/logout', {}, {
            withCredentials: true,
        })
            .then((response) => {
                console.log('Logout successful:', response.data)
            })
            .catch((error) => {
                console.error('Error during logout:', error)
            })

        user.value = null
        token.value = null
    }

    function setUser(userData) {
        user.value = userData
    }

    function setToken(tokenData) {
        token.value = tokenData
    }

    function setSession(sessionData) {
        session.value = sessionData
    }


    return {
        user,
        token,
        isLoggedIn,
        login,
        logout,
        getAuthUser,
        setUser,
        setToken,
        isAuthenticated,
        setSession,
    }
}, {
    persist: true
})