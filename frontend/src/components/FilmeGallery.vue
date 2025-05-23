<script setup>

import { ref, onMounted, defineProps } from 'vue'

import FilmeGrid from '../components/FilmeGrid.vue';
import FilmeCarrossel from '../components/FilmeCarrossel.vue';
import FilmePagination from './FilmePagination.vue';
import axios from 'axios';

const toast = useToast()

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
    },
    titulo: {
        type: String,
        default: 'Filmes'
    },
    classe: {
        type: String,
        default: ''
    },
    enableToggle: {
        type: Boolean,
        default: true
    },
    enableFilters: {
        type: Boolean,
        default: false
    },
    enableOrdem: {
        type: Boolean,
        default: true
    },
    enableAno: {
        type: Boolean,
        default: true
    },
    source: {
        type: String,
        default: 'tmdb'
    }
    
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
    tipo: 'grid',
    filtros: {
        genero: [],
        ano: null,
        ordem: 'popularity.desc',
        titulo: ''
    },
    generos: [],    
    ordem: [
        
        { id: 'popularity.asc', label: 'Popularity Asc' },
        { id: 'popularity.desc', label: 'Popularity Desc' },

        { id: 'title.asc', label: 'Title Asc' },
        { id: 'title.desc', label: 'Title Desc' },
        { id: 'revenue.asc', label: 'Revenue Asc' },
        { id: 'revenue.desc', label: 'Revenue Desc' },
        { id: 'vote_count.asc', label: 'Vote Count Asc' },
        { id: 'vote_count.desc', label: 'Vote Count Desc' },
        { id: 'vote_average.asc', label: 'Vote Average Asc' },
        { id: 'vote_average.desc', label: 'Vote Average Desc' },
        { id: 'original_title.asc', label: 'Original Title Asc' },
        { id: 'original_title.desc', label: 'Original Title Desc' },
        { id: 'primary_release_date.asc', label: 'Release Date Asc' },
        { id: 'primary_release_date.desc', label: 'Release Date Desc' },
    ], 
    search_type: 'genero'
});

onMounted(() => {
    data.value.tipo = props.tipo;
    data.value.enablePagination = props.enablePagination;
    
    let generos = JSON.parse(localStorage.getItem('generos'));

    if(generos) {
        data.value.generos = generos;
    } else {
        getGeneros();
    }        
    
    if(props.enableFilters && props.source != 'database'){
        data.value.search_type = 'genero';
        data.value.filtros.genero = [12];
        getFilmes(1, 'genero');
    }else{
        getFilmes(1);
    }

});

const toggleTipo = () => {
    data.value.tipo = data.value.tipo === 'grid' ? 'carrossel' : 'grid';
};

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

// Limpar filtros
function limparFiltros() {
    data.value.filtros.genero = [];
    data.value.filtros.ano = null;
    data.value.filtros.ordem = 'popularity.desc';
    data.value.filtros.titulo = '';
    data.value.search_type = 'genero';
    getFilmes(1, 'genero');
}

/**
 * Função para buscar os filmes dinâmicamente
 */
function getFilmes(page, tipo = null) {

    if(tipo == 'titulo' && data.value.filtros.titulo == ''){
        toast.add({
            title: 'Atenção',
            description: 'Você precisa informar um título para buscar.',
            icon: 'material-symbols:check-rounded',
            duration: 2000
        })
        return;
    }

    let url = '';

    if(props.source == 'database' && tipo == 'genero'){
        url = '/api/filmes/meus-filmes/buscar/genero'
    }else if(props.source == 'database' && tipo == 'titulo'){
        url = '/api/filmes/meus-filmes/buscar'
    }else if(props.source == 'tmdb' && tipo == 'genero'){
        url = '/api/filmes/buscar/genero';
    }else if(props.source == 'tmdb' && tipo == 'titulo'){
        url = '/api/filmes/buscar/';
    }else{
        url = props.api_url
    }

    data.value.loading = true;
    axios.get(url, {
        params: {
            page: page,
            ano: data.value.filtros.ano,
            genero: data.value.filtros.genero,
            ordem: data.value.filtros.ordem,
            titulo: data.value.filtros.titulo,
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
        data.value.loading = false;
        data.value.filmes = [];
    });

}

const paginatePrev = () => {
    let page = data.value.pagination.currentPage

    if (page < 1 || page > data.value.pagination.totalPages) return;

    data.value.pagination.currentPage--;
    if(props.enableFilters && data.value.search_type === 'genero'){
        getFilmes(data.value.pagination.currentPage, 'genero');
    }else if(props.enableFilters && data.value.search_type === 'titulo'){
        getFilmes(data.value.pagination.currentPage, 'titulo');
    }else{
        getFilmes(data.value.pagination.currentPage);
    }
};

const paginateNext = () => {
    let page = data.value.pagination.currentPage

    if (page < 1 || page > data.value.pagination.totalPages) return;

    data.value.pagination.currentPage++;
    if(props.enableFilters && data.value.search_type === 'genero'){
        getFilmes(data.value.pagination.currentPage, 'genero');
    }else if (props.enableFilters && data.value.search_type === 'titulo'){
        getFilmes(data.value.pagination.currentPage, 'titulo');
    }else{
        getFilmes(data.value.pagination.currentPage);
    }
};


</script>

<template>

    <div v-if="props.enableFilters" class="px-5 py-5 mb-10 bg-gray-800">
        <div class="text-right mb-5">
            <a href="#" class="text-sm py-1 px-5 col-span-2" @click="limparFiltros()">Limpar</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12">

            <div class="col-span-3 grid grid-cols-1 items-end text-left">
                <div class="grid grid-cols-1">
                    <label class="col-span-1 pl-3">Gênero:</label>
                    <UInputMenu v-model="data.filtros.genero" multiple :items="data.generos" value-key="id" class="col-span-2" />
                </div>
            </div>
            
            <div v-if="props.enableOrdem" class="my-2 lg:my-0 col-span-2 grid grid-cols-1 items-end text-left lg:ml-5">
                <div class="grid grid-cols-1">
                    <label class="col-span-1">Ordem</label>
                    <USelect v-model="data.filtros.ordem" :items="data.ordem" value-key="id" class="col-span-2" />
                </div>
            </div>

            <div class="my-2 lg:my-0 col-span-4 grid grid-cols-12 gap-4 lg:ml-5 lg:col-span-3 lg:grid-cols-2 lg:mr-2 items-end text-left">
                <div v-if="props.enableAno" class="grid col-span-3 lg:col-span-1">
                    <label class="col-span-1 pl-3">Ano:</label>
                    <UInput v-model="data.filtros.ano" type="number" placeholder="2025" min="1888" class="col-span-1"/>
                </div>

                <div class="col-span-1 items-end ">
                    <br>
                    <button @click="getFilmes(1,'genero')" class="col-span-3 bg-purple-700 py-1 align-right px-3">Filtrar</button>
                </div>
            </div>


            <!--  -->
            <div class="my-2 lg:my-0 col-span-4 grid grid-cols-12 gap-4 items-end">
                <UInput icon="i-lucide-search"  v-model="data.filtros.titulo" type="text" placeholder="Buscar por título..." class="col-span-9"/>
                <button @click="getFilmes(1,'titulo')" class="col-span-3 bg-purple-700 py-1 align-right">Buscar</button>
            </div>

        </div>

    </div>

    <div :class="`${props.classe} items-center py-5 px-10 rounded-lg`">

        <div class="grid grid-cols-1 gap-4 mb-5">
            <div class="col-span-1">
                <h2 class="text-3xl font-bold mb-4 text-left ml-10">{{ titulo }}</h2>
            </div>
            <div class="col-span-1 flex justify-end">
                <button v-if="props.enableToggle" @click="toggleTipo()">
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

            <FilmePagination v-if="data.tipo === 'grid' && data.enablePagination" :pagination="data.pagination" @paginate-next="paginateNext" @paginate-prev="paginatePrev" class="flex justify-center mb-5"></FilmePagination>
            
            <!-- Para telas com mais de 768px de largura e com o tipo grid -->
            <FilmeGrid v-if="data.tipo === 'grid'" class="grid md:flex-row justify-between hidden sm:block md:block" :filmes="data.filmes"></FilmeGrid>
            
            <!-- Para telas com menos de 640px de largura-->
            <div v-if="data.tipo === 'grid'"  class="block sm:hidden md:hidden lg:hidden">
                <FilmeCarrossel classe="max-w-lg" :filmes="data.filmes" tamanho="1/1"></FilmeCarrossel>
            </div>
            
            <!-- Para telas com menos de 768x de largura -->
            <div v-if="data.tipo === 'carrossel'" class="hidden sm:block md:hidden lg:hidden">
                <FilmeCarrossel classe="max-w-lg" :filmes="data.filmes" tamanho="1/3"></FilmeCarrossel>
            </div>

            <!-- Para telas com mais de 768px de largura e com o tipo carrossel -->
            <div v-if="data.tipo === 'carrossel'" class="hidden md:block lg:hidden">
                <FilmeCarrossel classe="max-w-270" :filmes="data.filmes" tamanho="1/3"></FilmeCarrossel>
            </div>
            
            <!-- Para telas com mais de 1024px de largura e com o tipo carrossel -->
            <div v-if="data.tipo === 'carrossel'" class="hidden md:hidden lg:block">
                <FilmeCarrossel classe="max-w-270" :filmes="data.filmes" tamanho="1/5"></FilmeCarrossel>
            </div>

            <FilmePagination v-if="data.tipo === 'grid' && data.enablePagination" :pagination="data.pagination" @paginate-next="paginateNext" @paginate-prev="paginatePrev" class="flex justify-center mt-4"></FilmePagination>

        </div>

    </div>

</template>