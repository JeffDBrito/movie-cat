
<script setup>

import { ref, onMounted, defineProps } from 'vue'
import { useRoute } from 'vue-router'
import { auth } from '../stores/auth'
import axios from 'axios'

const authStore = auth()

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

const data = ref({
    image_url: '',
})

onMounted(() => {
    if(props.filme.poster_path) {
        data.value.image_url = `https://image.tmdb.org/t/p/w500${props.filme.poster_path}`
    } else if(props.filme.backdrop_path) {
        data.value.image_url = `https://image.tmdb.org/t/p/w500${props.filme.backdrop_path}`
    } else {
        data.value.image_url = 'https://placehold.co/500x750?text=Sem+Imagem&font=roboto&textsize=200&bg=000000&txtcolor=ffffff'
    }
    
})

function favoritar() {

    props.filme.is_favorito = true
    
    axios.post('/api/filmes/favoritar', {
        tmdb_id: props.filme.id
    })
    .then(response => {
        console.log('Filme favoritado com sucesso:', response.data)
    })
    .catch(error => {
        console.error('Erro ao favoritar filme:', error)
    })

}

function desfavoritar() {

    props.filme.is_favorito = false
    
    axios.post('/api/filmes/desfavoritar', {
        tmdb_id: props.filme.id
    })
    .then(response => {
        console.log('Filme desfavoritado com sucesso:', response.data)
    })
    .catch(error => {
        console.error('Erro ao desfavoritar filme:', error)
    })

}

</script>

<template>
    
    <UCard :class="`${ classe } h-full`">
        <template #header>
            <div class="">
                <img :src="data.image_url" alt="Filme Poster" class="w-full h-auto rounded-lg shadow-lg">
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
                    
                    <!-- Favoritado -->
                    <button v-if="filme.is_favorito" @click="desfavoritar()">
                        <UIcon name="material-symbols-light:star" class="h-10 w-10 text-yellow-500" />
                    </button>

                    <!-- Não favoritado -->
                    <button v-else @click="favoritar()">
                        <UIcon name="material-symbols-light:star-outline" class="h-10 w-10 text-yellow-500" />
                    </button>
                    
                </div>
            </div>
        </template>

    </UCard>    

</template>