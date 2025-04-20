<script setup>

import { ref, onMounted, defineProps } from 'vue'

import FilmeGrid from '../components/FilmeGrid.vue';
import FilmeCarrossel from '../components/FilmeCarrossel.vue';
import FilmePagination from './FilmePagination.vue';
import axios from 'axios';

const props = defineProps({
    tipo: {
        type: String,
        default: 'grid'
    },
    enablePagination: {
        type: Boolean,
        default: false
    },
    api_url: {
        type: String,
        default: '',
        required: true
    },
    titulo: {
        type: String,
        default: 'Filmes'
    },
    
});

const data = ref({
    filmes: [],
    pagination: {
        type: Object,
        default: ({
            currentPage: 1,
            totalPages: 1,
            totalItems: 1,
            itemsPerPage: 20,
        })
    },
    loading: false,
    tipo: 'grid'
});

onMounted(() => {
    data.value.tipo = props.tipo;
    data.value.enablePagination = props.enablePagination;
    console.log('Tipo inicial:', data.value.tipo);
    getFilmes(1);
});

const toggleTipo = () => {
    data.value.tipo = data.value.tipo === 'grid' ? 'carrossel' : 'grid';
};

function getFilmes(page) {

    data.value.loading = true;
    
    axios.get(props.api_url, {
        params: {
            page: page
        }
    })
    .then(response => {

        data.value.filmes = response.data.results;

        data.value.pagination.currentPage = response.data.page
        data.value.pagination.totalPages = response.data.total_pages
        data.value.pagination.totalItems = response.data.total_results
        data.value.pagination.itemsPerPage = response.data.results.length

        data.value.loading = false;
        
    })
    .catch(error => {
        console.error('Erro ao buscar filmes:', error);
    });

}

const paginatePrev = () => {
    let page = data.value.pagination.currentPage

    if (page < 1 || page > data.value.pagination.totalPages) return;

    data.value.pagination.currentPage--;
    getFilmes(data.value.pagination.currentPage);
};

const paginateNext = () => {
    let page = data.value.pagination.currentPage

    if (page < 1 || page > data.value.pagination.totalPages) return;

    data.value.pagination.currentPage++;
    getFilmes(data.value.pagination.currentPage);
};


</script>

<template>

    <div class=" items-center py-5 px-10 rounded-lg">

        <div class="grid grid-cols-1 gap-4 mb-5">
            <div class="col-span-1">
                <h2 class="text-3xl font-bold mb-4 text-left ml-10">{{ titulo }}</h2>
            </div>
            <div class="col-span-1 flex justify-end">
                <button @click="toggleTipo()">
                    <!-- Icon grid -->
                        <UIcon v-if="data.tipo === 'carrossel'" name="mdi:grid-large" class="text-white" />
                    <!-- Icon carrossel -->
                        <UIcon v-if="data.tipo === 'grid'" name="mdi:list-box-outline" class="text-white" />
                </button>
            </div>
        </div>

        <div v-if="data.loading" class="flex justify-center">
            <UIcon name="ooui:progress-spinner" class="text-white animate-spin" />
        </div>
        <div v-else-if="data.filmes.length === 0" class="flex justify-center">
            <p class="text-white">Nenhum filme encontrado.</p>
        </div>

        <div v-else class="bg-gray-800 rounded-lg p-4">    
            <!-- Para telas com menos de 640px de largura-->
            <div class="block sm:hidden md:hidden lg:hidden">
                <FilmeCarrossel classe="max-w-lg" :filmes="data.filmes" tamanho="1/1"></FilmeCarrossel>
            </div>
            
            <!-- Para telas com menos de 768x de largura -->
            <div class="hidden sm:block md:hidden lg:hidden">
                <FilmeCarrossel classe="max-w-lg" :filmes="data.filmes" tamanho="1/3"></FilmeCarrossel>
            </div>
            
            <!-- Para telas com mais de 768px de largura e com o tipo carrossel -->
            <div v-if="data.tipo === 'carrossel'" class="hidden md:hidden lg:block">
                <FilmeCarrossel classe="max-w-270" :filmes="data.filmes" tamanho="1/5"></FilmeCarrossel>
            </div>
            
            <!-- Para telas com mais de 768px de largura e com o tipo grid -->
            <FilmeGrid v-if="data.tipo === 'grid'" class="grid md:flex-row justify-between hidden md:block" :filmes="data.filmes"></FilmeGrid>

            <FilmePagination v-if="data.tipo === 'grid' && data.enablePagination" :pagination="data.pagination" @paginate-next="paginateNext" @paginate-prev="paginatePrev" class="flex justify-center mt-4"></FilmePagination>

        </div>

    </div>

</template>