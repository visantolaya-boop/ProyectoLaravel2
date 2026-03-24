<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import NavLink from '@/Components/NavLink.vue'; 
import StarIcon from '@/Components/StarIcon.vue';

const props = defineProps({
    reseña: Object
});

const statusStyles = {
    'Leyendo': 'bg-blue-100 text-blue-800 border-blue-200',
    'Terminado': 'bg-green-100 text-green-800 border-green-200',
    'Pendiente': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'Abandonado': 'bg-red-100 text-red-800 border-red-200',
    'default': 'bg-gray-100 text-gray-800 border-gray-200'
};

const getStatusClass = (estado) => {
    return statusStyles[estado] || statusStyles['default'];
};
</script>

<template>
    <Head title="Detalle de Reseña" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-4 sm:px-0">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Reseña: <span class="text-blue-600 block sm:inline">{{ reseña?.libros?.titulo || 'Sin título' }}</span>
                </h2>
                <Link :href="route('reseñas.editar', reseña?.id)"
                    class="w-full sm:w-auto text-center bg-blue-600  text-white px-6 py-2.5 rounded-xl shadow-md text-sm font-bold">
                    Editar Reseña
                </Link>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">

                    <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/50">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">Periodo de lectura</p>
                                <p class="text-gray-700 font-medium text-sm sm:text-base">
                                    {{ reseña?.inicio_lectura || '—' }} 
                                    <span class="text-gray-300 mx-1">/</span> 
                                    {{ reseña?.final_lectura || '—' }}
                                </p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">Tiempo Invertido</p>
                                <p class="text-gray-700 font-medium text-sm sm:text-base">
                                    <span class="text-blue-600 font-bold">{{ reseña?.tiempo_lectura || 0 }}</span> días
                                </p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">Estado actual</p>
                                <div>
                                    <span :class="['inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border', getStatusClass(reseña?.estado)]">
                                        <span class="w-2 h-2 mr-2 rounded-full bg-current"></span>
                                        {{ reseña?.estado || 'Desconocido' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 sm:px-8 py-4 flex items-center space-x-3 border-b border-gray-50">
                        <p class="text-xs sm:text-sm font-bold text-gray-500 uppercase">Puntuación:</p>
                        <div class="flex">
                            <StarIcon 
                                v-for="i in 5" 
                                :key="i" 
                                class="w-5 h-5" 
                                :class="i <= (reseña?.puntuacion || 0) ? 'text-green-400 fill-current' : 'text-gray-200'" 
                            />
                        </div>
                    </div>

                    <div class="p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Análisis del Lector
                        </h3>
                        
                        <div class="prose max-w-none text-gray-600 leading-relaxed italic border-l-4 border-blue-500/20 pl-6 py-2 bg-blue-50/30 rounded-r-lg">
                            <p class="whitespace-pre-line text-sm sm:text-base">
                                {{ reseña?.reseña || 'El lector aún no ha escrito sus pensamientos sobre esta obra.' }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>