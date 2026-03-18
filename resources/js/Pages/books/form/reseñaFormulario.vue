<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Input from '@/Components/Input.vue';
import Label from '@/Components/Label.vue'; // Si no tienes este componente, usa <label> con clases
import { watch } from 'vue';

const props = defineProps({
    reseña: Object
});

const form = useForm({
    inicio_lectura: props.reseña.inicio_lectura,
    final_lectura: props.reseña.final_lectura,
    tiempo_lectura: props.reseña.tiempo_lectura,
    estado: props.reseña.estado,
    puntuacion: props.reseña.puntuacion,
    reseña: props.reseña.reseña,
});

const actualizar = () => {
    form.put(route('reseñas.update', props.reseña.id));
};

const volverAtras = () => {
    window.history.back();
};

const calcularDias = () => {
    if (form.inicio_lectura && form.final_lectura) {
        const inicio = new Date(form.inicio_lectura);
        const final = new Date(form.final_lectura);

        const diffTime = final - inicio;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        form.tiempo_lectura = diffDays >= 0 ? diffDays : 0;
    }
};

watch(() => [form.inicio_lectura, form.final_lectura], ([newInicio, newFinal]) => {
    calcularDias();

    if (newInicio && newFinal) {
        const fechaInicio = new Date(newInicio);
        const fechaFinal = new Date(newFinal);

        if (fechaFinal >= fechaInicio) 
            form.estado = 'Terminado';
    }
        
});


</script>

<template>
    <Head title="Editar Reseña" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight px-4 sm:px-0">
                Editar Reseña: <span class="text-blue-600 font-medium block sm:inline">{{ reseña?.libros?.titulo }}</span>
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-5 sm:p-8">
                        <form @submit.prevent="actualizar" class="space-y-6 sm:space-y-8">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div class="space-y-2">
                                    <Label class="text-gray-700 font-bold ml-1 text-sm sm:text-base">Inicio de la lectura</Label>
                                    <Input type="date" v-model="form.inicio_lectura"
                                        class="w-full border-gray-200 focus:ring-blue-500 rounded-xl shadow-sm" />
                                    <div v-if="form.errors.inicio_lectura" class="text-red-400 text-xs italic">
                                        {{ form.errors.inicio_lectura }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-gray-700 font-bold ml-1 text-sm sm:text-base">Fin de la lectura</Label>
                                    <Input type="date" v-model="form.final_lectura"
                                        class="w-full border-gray-200 focus:ring-blue-500 rounded-xl shadow-sm" />
                                    <div v-if="form.errors.final_lectura" class="text-red-400 text-xs italic">
                                        {{ form.errors.final_lectura }}
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                                <div class="space-y-2">
                                    <Label class="text-gray-700 font-bold ml-1 text-sm sm:text-base">Tiempo (días)</Label>
                                    <Input type="number" min="0" v-model="form.tiempo_lectura"
                                        class="w-full border-gray-200 rounded-xl shadow-sm bg-gray-50 cursor-not-allowed" 
                                        readonly />
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-gray-700 font-bold ml-1 text-sm sm:text-base">Estado</Label>
                                    <select v-model="form.estado"
                                        class="w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-all">
                                        <option value="Leyendo">Leyendo</option>
                                        <option value="Terminado">Terminado</option>
                                        <option value="Abandonado">Abandonado</option>
                                        <option value="Pendiente">Pendiente</option>
                                    </select>
                                </div>

                                <div class="space-y-2 sm:col-span-2 md:col-span-1">
                                    <Label class="text-gray-700 font-bold ml-1 text-sm sm:text-base">Puntuación (0-5)</Label>
                                    <Input type="number" min="0" max="5" v-model="form.puntuacion"
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label class="text-gray-700 font-bold ml-1 text-sm sm:text-base">Tu Reseña Personal</Label>
                                <textarea v-model="form.reseña" rows="6"
                                    class="w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-all text-sm sm:text-base"
                                    placeholder="Escribe aquí tus impresiones sobre el libro..."></textarea>
                                <div v-if="form.errors.reseña" class="text-red-400 text-xs italic">
                                    {{ form.errors.reseña }}
                                </div>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                                <button type="button" @click="volverAtras"
                                    class="w-full sm:w-auto px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold uppercase tracking-widest rounded-xl transition-all active:scale-95 text-center text-xs">
                                    Cancelar
                                </button>
                                
                                <button type="submit" :disabled="form.processing"
                                    class="w-full sm:w-auto px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-green-100 transition-all active:scale-95 disabled:opacity-50 text-center text-xs">
                                    {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>