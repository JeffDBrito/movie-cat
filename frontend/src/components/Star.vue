<script setup>

import { defineProps } from 'vue'
import axios from 'axios'

const toast = useToast()

const props = defineProps({
    filme: {
        type: Object,
        required: true
    }
})

function favoritar() {
    props.filme.is_favorito = true
    axios.post('/api/filmes/favoritar', {
        tmdb_id: props.filme.id
    })
    .then(response => {
        toast.add({
            title: 'Filme favoritado com sucesso!',
            description: 'Agora esse filme está na sua lista de favoritos.',
            icon: 'material-symbols:check-rounded',
            duration: 2000
        })
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
        toast.add({
            severity: 'success',
            description: 'Esse filme foi removido da sua lista de favoritos.',
            icon: 'material-symbols:check-rounded',
            duration: 2000
        })
    })
    .catch(error => {
        console.error('Erro ao desfavoritar filme:', error)
    })
}

</script>


<template>
    <!-- Favoritado -->
    <button v-if="filme.is_favorito" @click="desfavoritar()">
        <UIcon name="material-symbols-light:star" class="h-10 w-10 text-yellow-500" />
    </button>

    <!-- Não favoritado -->
    <button v-else @click="favoritar()">
        <UIcon name="material-symbols-light:star-outline" class="h-10 w-10 text-yellow-500" />
    </button>
</template>