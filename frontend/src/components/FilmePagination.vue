<script setup>

import { defineProps } from 'vue'

const props = defineProps({
    pagination: {
        type: Object,
        required: true,
        default:({
            currentPage: 1,
            totalPages: 1,
            totalItems: 1,
            itemsPerPage: 20,
        })
    },
})

</script>

<template>

    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-2">
            <button v-if="pagination.currentPage > 1" @click="$emit('paginate-prev')" :disabled="pagination.currentPage === 1">
                <UIcon name="ooui:arrow-next-rtl"/>
            </button>
        </div>
        <div class="col-span-2">
            <span>Página {{ pagination.currentPage }} de {{ pagination.totalPages }}</span>
        </div>
        <div class="col-span-2">
            <button v-if="pagination.currentPage < pagination.totalPages" @click="$emit('paginate-next')" :disabled="pagination.currentPage === pagination.totalPages">
                <UIcon name="ooui:arrow-next-ltr"/>
            </button>
        </div>
    </div>

</template>