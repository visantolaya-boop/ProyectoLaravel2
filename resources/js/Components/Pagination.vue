<script setup>
import { Link } from '@inertiajs/inertia-vue3';
defineProps({
    links: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <div v-if="links.length > 3" class="mt-8 flex items-center justify-center gap-1">
        <template v-for="(link, index) in links" :key="index">
            
            <span
                v-if="!link.url"
                v-html="link.label"
                class="px-4 py-2 text-sm text-gray-400 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed"
            ></span>

            <Link
                v-else
                :href="link.url"
                v-html="link.label"
                class="px-4 py-2 text-sm font-medium border transition-all duration-200 rounded-lg shadow-sm"
                :class="{
                    // Estilo para la página ACTIVA
                    'bg-indigo-500 border-indigo-600 text-white shadow-indigo-200': link.active,
                    // Estilo para las páginas NO activas
                    'bg-white border-gray-200 text-gray-700 hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50': !link.active
                }"
            />
            
        </template>
    </div>
</template>