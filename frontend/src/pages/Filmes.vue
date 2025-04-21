<script setup>

import FilmeGallery from '../components/FilmeGallery.vue';

</script>
<template>

    <div class="w-full">
        <div>
            <div class="mb-10">
                <h1>Filmes</h1>
            </div>

            <div class=" items-center py-5 px-10 rounded-lg">                
                <FilmeGallery tipo="grid" titulo="Resultados" api_url="api/filmes/categoria/popular" :enableFilters="true" :enablePagination="true"></FilmeGallery>
            </div>            
        </div>        
    </div>

</template>