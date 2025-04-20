import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { router } from './router'
import { createPinia } from 'pinia'
import piniaPersistedstate from 'pinia-plugin-persistedstate'
import axios from 'axios'
import ui from '@nuxt/ui/vue-plugin'

axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true;
axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL

const pinia = createPinia()
pinia.use(piniaPersistedstate)

createApp(App)
    .use(pinia)
    .use(router)
    .use(ui)
    .mount('#app')