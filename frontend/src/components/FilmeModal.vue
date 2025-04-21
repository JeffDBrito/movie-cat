<script setup>
import { ref, defineProps, onMounted } from 'vue';
import { auth } from '../stores/auth';
import axios from 'axios';
import Star from './Star.vue';

const authStore = auth();

const props = defineProps({
    filme: {
        type: Object,
        required: true
    }
});

const data = ref({
    image_url: '',
    release_date: '',
    titulo: '',
    generos: []
});

function formatarTitulo(titulo){
    return titulo.replaceAll("\"", '');
}

function formatarVoteAverage(nota) {
    return nota.toFixed(1).replace('.', ',');
}

// Generos
function getGeneros(){

    axios.get('/api/filmes/generos')
    .then(response => {
        data.value.generos = response.data.genres;
        filtrarGeneros(data.value.generos);
        localStorage.setItem('generos', JSON.stringify(data.value.generos));
    })
    .catch(error => {
        console.error('Erro ao buscar gêneros:', error);
    });
}

function filtrarGeneros(generos) {

    // Muda o atributo nome para label
    var formatados = generos.map(genero => {
        return {
            id: genero.id,
            label: genero.name
        }
    });

    data.value.generos = formatados;
}

onMounted(() => {
    if(props.filme.poster_path) {
        data.value.image_url = `https://image.tmdb.org/t/p/w500${props.filme.poster_path}`;
    } else if(props.filme.backdrop_path) {
        data.value.image_url = `https://image.tmdb.org/t/p/w500${props.filme.backdrop_path}`;
    } else {
        data.value.image_url = 'https://placehold.co/500x750?text=Sem+Imagem&font=roboto&textsize=200&bg=000000&txtcolor=ffffff';
    }

    data.value.release_date = props.filme.release_date ? props.filme.release_date.split('-')[0] : 'N/A';

    let generos = JSON.parse(localStorage.getItem('generos'));

    if(generos) {
        data.value.generos = generos;
    } else {
        getGeneros();
    }
    
});

</script>
<template>

    <!-- :class="`${authStore.isAuthenticated() ? 'col-span-4' : 'col-span-6'}`" -->
    <UModal
    :title="'Detalhes do filme'" 
    :close="{ color: 'secondary', variant: 'outline', class: 'rounded-full'}" 
    :ui="{ width: 'md:max-w-4xl'}"
    >
        <UButton label="Mais" color="white" class="bg-purple-700 py-1 px-5 w-full justify-center "/>
        <template #body >
            <div class="grid grid-cols-2 gap-4" >
                
                <div class="col-span-1">
                    <img :src="data.image_url" alt="Imagem do filme" class="rounded-lg shadow-lg mb-4 w-full h-auto">
                </div>
                
                <div class="col-span-1">
                    <div class="text-right col-span-2 items-center justify-center">
                        <Star :filme="filme" />
                    </div>
                    <h2 class="text-2xl font-bold mb-5">{{ formatarTitulo(filme.title) }}</h2>
                    <p>
                        <strong>Gêneros: </strong>
                    </p>
                    <div class="flex flex-wrap">
                        <span v-for="(genero, index) in filme.genre_ids" :key="index" :class="`${data.generos.find(g => g.id === genero)? '' : 'hidden'} text-sm text-black bg-purple-600 rounded-full px-2 py-1 mr-2 mt-2 `">
                            {{ data.generos.find(g => g.id === genero)?.label }} 
                        </span>
                    </div>
                    <p class="my-3"><strong>Nota: </strong><br>{{ formatarVoteAverage(filme.vote_average) }}</p>
                    <p class="my-3"><strong>Ano de lançamento: </strong><br>{{data.release_date}}</p>
                </div>
                
                <div class="col-span-2">
                    <p><strong>Sinopse: </strong><br> {{ filme.overview }}</p>
                </div>
            </div>
        </template>
    </UModal>

</template>

<style scoped>

    .modal-body {
        /* max-height: 80vh;
        overflow-y: auto; */
        /* width: 500px; */
    }

</style>