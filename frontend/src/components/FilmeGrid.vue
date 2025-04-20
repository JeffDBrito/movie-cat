<script setup>

import { defineProps } from 'vue'
import FilmeCard from '../components/FilmeCard.vue'

const props = defineProps({
    filmes: {
        type: Array,
        required: true
    },

    classe: {
        type: String,
        default: ''
    },
})

</script>

<template>

    <div id="filmes_content" >
        <div class="grid grid-cols-2 grid-cols-2 md:grid-cols-4 lg:grid-cols-10 text-right col-span-12">
            <FilmeCard v-for="filme in filmes" key="filme.id" classe="col-span-2 sm:col-span-1 md:col-span-1 lg:col-span-2 text-center bg-gray-900 mx-2" :filme="filme"></FilmeCard>
        </div>
    </div>

</template>