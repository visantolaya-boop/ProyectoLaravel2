<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_libros: 0,
            estrellas: { "5": 0, "4": 0, "3": 0, "2": 0, "1": 0, "0": 0 },
            estados: { terminados: 0, leyendo: 0, pendiente: 0, abandonados: 0 }
        })
    }
})

const getPorcentaje = (valor) => {
    const num = Number(valor);
    if (!props.stats.total_libros || props.stats.total_libros === 0) return 0;
    return (num / props.stats.total_libros) * 100;
};

const ratioFinalizacion = computed(() => {
    if (!props.stats.total_libros || props.stats.total_libros === 0) return 0;
    return ((props.stats.estados.terminados / props.stats.total_libros) * 100).toFixed(0);
});

const promedioPuntuacion = computed(() => {
    let totalPuntos = 0;
    let totalVotos = 0;

    Object.entries(props.stats.estrellas).forEach(([estrella, cantidad]) => {
        const pts = Number(estrella);
        const cant = Number(cantidad);
        if (pts > 0) { 
            totalPuntos += pts * cant;
            totalVotos += cant;
        }
    });

    return totalVotos > 0 ? (totalPuntos / totalVotos).toFixed(1) : "0.0";
});

const estrellasOrdenadas = computed(() => {
    return Object.entries(props.stats.estrellas).sort((a, b) => b[0] - a[0]);
});
</script>

<template>

    <Head title="Resumen de mi Biblioteca" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl text-gray-800 tracking-tight">
                Mi Biblioteca <span class="text-gray-400 font-normal">/ Resumen</span>
            </h2>
        </template>

        <div class="py-8 bg-gray-50/50 min-h-screen">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                        <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Libros totales</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.total_libros }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                        <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Rating medio</p>
                            <p class="text-2xl font-bold text-gray-900">{{ promedioPuntuacion }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Finalizados</p>
                            <p class="text-2xl font-bold text-gray-900">{{ ratioFinalizacion }}%</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Estado de lectura</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                                <span class="text-emerald-600 text-[0.6rem] font-bold uppercase tracking-wider">Leídos</span>
                                <p class="text-3xl font-bold text-emerald-700 mt-1">{{ stats.estados.terminados }}</p>
                            </div>
                            <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                                <span class="text-blue-600 text-[0.6rem] font-bold uppercase tracking-wider">Leyendo</span>
                                <p class="text-3xl font-bold text-blue-700 mt-1">{{ stats.estados.leyendo }}</p>
                            </div>
                            <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-100">
                                <span
                                    class="text-orange-600 text-[0.6rem] font-bold uppercase tracking-wider">Pendientes</span>
                                <p class="text-3xl font-bold text-orange-700 mt-1">{{ stats.estados.pendiente }}</p>
                            </div>
                            <div class="p-4 bg-rose-50/50 rounded-2xl border border-rose-100">
                                <span
                                    class="text-rose-600 text-[0.6rem] font-bold uppercase tracking-wider">Abandonados</span>
                                <p class="text-3xl font-bold text-rose-700 mt-1">{{ stats.estados.abandonados }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Distribución de estrellas</h3>
                        <div class="space-y-4">
                            <div v-for="[star, count] in estrellasOrdenadas" :key="star"
                                class="flex items-center group">
                                <div
                                    class="w-12 text-sm font-bold text-gray-400 group-hover:text-yellow-500 transition-colors">
                                    {{ star }} ★
                                </div>
                                <div class="flex-1 h-3 bg-gray-100 rounded-full mx-4 overflow-hidden">
                                    <div class="h-full bg-yellow-400 rounded-full transition-all duration-700 ease-out"
                                        :style="{ width: getPorcentaje(count) + '%' }"></div>
                                </div>
                                <div class="w-8 text-right text-sm font-semibold text-gray-600">
                                    {{ count }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>