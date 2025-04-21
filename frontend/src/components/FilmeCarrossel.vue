<script setup>
import { onMounted, defineProps } from 'vue'
import FilmeCard from './FilmeCard.vue';

const props = defineProps({
    classe: {
        type: String,
        default: ''
    },
    filmes: {
        type: Array,
        required: true
    },
    tamanho: {
        type: String,
        default: '1/5'
    },
    arrows: {
        type: Boolean,
        default: true
    },
    loop: {
        type: Boolean,
        default: true
    },
    dots: {
        type: Boolean,
        default: false
    }
    
})

function formatarTamanho(tamanho){
    return "basis-"+tamanho
}

onMounted(() => {

})

</script>

<template>

    <UCarousel v-slot="{ item }" 
    :class="`w-full mx-auto ${classe}`" 
    :dots="dots"
    :loop="loop"
    :arrows="arrows" 
    :autoplay="{ delay: 3000 }" 
    :items="filmes" 
    :ui="{ item: formatarTamanho(tamanho) }" 
    :prev="{ 
        variant: 'solid',
        class: 'rounded-full'
    }"
    :next="{ 
        variant: 'solid'
    }"
    >
        <FilmeCard classe="col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-2 text-center m-2 md:block" :filme="item"></FilmeCard>
    </UCarousel>

</template>