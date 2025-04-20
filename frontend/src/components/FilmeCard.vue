
<script setup>

import { ref, onMounted, defineProps } from 'vue'
import { useRoute } from 'vue-router'
import { auth } from '../stores/auth'

const authStore = auth()
const route = useRoute()

const props = defineProps({

    filme: {
        type: Object,
        required: true
    },

    classe: {
        type: String,
        default: ''
    },

})

</script>

<template>
    
    <UCard :class="`${ classe } h-full`">
        <template #header>
            <div class="">
                <img :src="`https://image.tmdb.org/t/p/w500${filme.poster_path}`" alt="Filme Poster" class="w-full h-auto rounded-lg shadow-lg">
            </div>
        </template>
        
        <div class="relative bg-gray-900 bg-opacity-50 p-4 rounded-lg">

            <div clas="row-span-1 text-left my-10">
                <p class="font-bold text-lg line-clamp-2 h-[3.5rem]">{{filme.title}}</p>
                <span class="text-sm text-left align-left">{{filme.release_date}}</span>
            </div>

        </div>

        <template #footer>
            <div class="grid grid-cols-6 items-center justify-center">
                <button :class="`bg-purple-700 py-1 px-5 ${authStore.isAuthenticated() ? 'col-span-4' : 'col-span-6'}`">Mais</button>
                <div v-if="authStore.isAuthenticated()" class="pl-4 col-span-2 text-center items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 txt-center" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                    </svg>
                </div>
            </div>
        </template>

    </UCard>    

</template>