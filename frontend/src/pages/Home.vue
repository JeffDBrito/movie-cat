<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { auth } from '../stores/auth'
import MovieCatLogo from '../components/MovieCatLogo.vue'

defineProps({
    msg: String,
})

onMounted(() => {
    const authStore = auth()
    authStore.isAuthenticated()
})

const count = ref(0)
</script>

<template>
    <div class="container">
        <div id="hero_banner" class="mb-10 text-white grid grid-cols-1 md:grid-cols-12 md:flex-row justify-between items-center p-10 rounded-lg">

            <div class="hero_banner_logo bg-gray-300 col-span-6 rounded-lg py-10 px-5 mb-10">
                <MovieCatLogo cor="preta" :home="false" tamanho="1/2"></MovieCatLogo>
            </div>

            <div class="hero_banner_content col-span-6 text-right ml-10">
                <h1 class="font-bold text-left">Boas-vindas</h1>
                <p class="text-3xl mb-10 font-bold text-left">Ao seu catálogo de Filmes Favorito</p>
            </div>

        </div>

        <div id="home_content">
            <FilmeGallery classe="mt-10" tipo="carrossel" :enableToggle="false" titulo="FILMES POPULARES" api_url="api/filmes/categoria/popular" :enablePagination="false"></FilmeGallery>
            <FilmeGallery classe="mt-10" tipo="carrossel" :enableToggle="false" titulo="MELHORES AVALIAÇÕES" api_url="api/filmes/categoria/top_rated" :enablePagination="false"></FilmeGallery>
            <FilmeGallery classe="mt-10" tipo="carrossel" :enableToggle="false" titulo="NOS CINEMAS" api_url="api/filmes/categoria/now_playing" :enablePagination="false"></FilmeGallery>
        </div>

        
    </div>
</template>

<style scoped>
#hero_banner .hero_banner_logo{
    /* background-color: #1a1a1a;   */
    border-radius: 10px;
}

.read-the-docs {
    color: #888;
}
</style>
