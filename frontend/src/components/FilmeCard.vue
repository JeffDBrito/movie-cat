
<script setup>

import { ref, onMounted, defineProps } from 'vue'
import { auth } from '../stores/auth'
import FilmeModal from './FilmeModal.vue'
import Star from './Star.vue'

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
    release_date: '',
})

onMounted(() => {
    if(props.filme.poster_path) {
        data.value.image_url = `https://image.tmdb.org/t/p/w500${props.filme.poster_path}`
    } else if(props.filme.backdrop_path) {
        data.value.image_url = `https://image.tmdb.org/t/p/w500${props.filme.backdrop_path}`
    } else {
        data.value.image_url = 'https://placehold.co/500x750?text=Sem+Imagem&font=roboto&textsize=200&bg=000000&txtcolor=ffffff'
    }

    data.value.release_date = props.filme.release_date ? props.filme.release_date.split('-')[0] : 'N/A'
    
})

</script>

<template>
    
    <UCard :class="`${ classe } h-full`">
        <template #header>
            <div class="">
                <img :src="data.image_url" alt="Filme Poster" class="w-full h-auto rounded-lg shadow-lg">
            </div>
        </template>
        
        <div class="relative bg-gray-900 bg-opacity-50 rounded-lg">
            <div clas="row-span-1">
                <p class="font-bold text-lg line-clamp-2 h-[3.5rem]">{{filme.title.replaceAll("\"","")}}</p>
                <div class="text-right">
                    <span class="text-xs text-left align-left">{{data.release_date}}</span>
                </div>
            </div>

        </div>

        <template #footer>
            <div class="flex justify-between items-center">
            
                <FilmeModal :filme="filme"/>
                <div class="grid grid-cols-6 items-center justify-center">
                    <div v-if="authStore.isAuthenticated()" class="pl-4 col-span-2 text-center items-center justify-center">
                        
                        <Star :filme="filme" />
                        
                    </div>
                </div>
            </div>
        </template>

    </UCard>    

</template>