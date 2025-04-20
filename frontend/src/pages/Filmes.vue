<script setup>

import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { auth } from '../stores/auth';

import FilmeGallery from '../components/FilmeGallery.vue';


</script>
<template>

    <div class="filmes-page">

        <div>
            <div class="mb-10">
                <h1>Filmes</h1>
            </div>
            
            <div class=" items-center py-5 px-10 rounded-lg">
                
                <FilmeGallery tipo="grid" titulo="Populares" api_url="api/filmes/categoria/popular" :enablePagination="true"></FilmeGallery>
                
            </div>
        </div>
        
    </div>

</template>