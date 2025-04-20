<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()


const props = defineProps({
    cor: {
        type: String,
        default: 'branca'
    },
    tamanho: {
        type: String,
        default: '1/2'
    },
    home: {
        type: Boolean,
        default: false
    },
    classe: {
        type: String,
        default: ''
    }
})

</script>

<template>
    <div class="flex justify-center">
        <router-link v-if="home" to="/">
            <img :src="`src/assets/logo_${cor}.png`" :alt="`Logo ${cor}`" :class="`w-${tamanho} py-3 mx-auto mb-2 ${classe}`" />
        </router-link>
        <img v-else :src="`src/assets/logo_${cor}.png`" :alt="`Logo ${cor}`" :class="`w-${tamanho} py-3 mx-auto mb-2 ${classe}`" />
    </div>
</template>