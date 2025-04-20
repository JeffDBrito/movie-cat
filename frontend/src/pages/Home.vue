<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { auth } from '../stores/auth'
import MovieCatLogo from '../components/MovieCatLogo.vue'

defineProps({
    msg: String,
})

onMounted(() => {
    console.log('Home component mounted')
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
                <p class="text-black">Seu catálogo de filmes favoritos.</p>
            </div>

            <div class="hero_banner_content col-span-6 text-right pl-10">
                <h1 class="font-bold text-left">Boas-Vindas</h1>
                <p class="text-3xl mb-10 font-bold text-left">Ao seu catálogo de Filmes</p>
                <p class="text-lg">Encontre os melhores filmes.</p>
                <p class="text-lg">Acesse o nosso catálogo e selecione os seus favoritos.</p>
            </div>

        </div>

        <div id="home_content">
            
        </div>

        
    </div>
</template>

<style scoped>
#hero_banner .hero_banner_logo{
    /* background-color: #1a1a1a;   */
    border-radius: 10px;
}

#hero_banner .hero_banner_content{
    background-color: #1a1a1a;  
    border-radius: 10px;
}

.read-the-docs {
    color: #888;
}
</style>
