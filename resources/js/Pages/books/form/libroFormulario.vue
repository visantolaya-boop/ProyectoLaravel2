<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Input from '@/Components/Input.vue';
import Label from '@/Components/Label.vue'; // Usa <label class="font-bold text-gray-700"> si no tienes el componente
import { ref } from 'vue';

const props = defineProps({
    libro: Object,
    isUpdating: Boolean,
    ubicaciones: {
        type: Array,
        default: () => ([]),
    },
});

const form = useForm({
    _method: props.isUpdating ? 'put' : 'post',
    titulo: props.libro.titulo || '',
    autor: props.libro.autor || '',
    anio: props.libro.anio || '',
    editorial: props.libro.editorial || '',
    paginas: props.libro.paginas || '',
    genero: props.libro.genero || '',
    portada: null,
    ubicacion_id: props.libro.ubicacion_id ?? '',
});

const actualizar = () => {
    if (props.isUpdating) {
        form.post(route('books.update', props.libro.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('books.store'));
    }
};

const nuevaUbicacion = (event) => {
    if (event.target.value === 'nueva') {
        showModal.value = true;
        form.ubicacion_id = null;    }
}

const showModal = ref(false),
    ubicacionForm = useForm({
        nombre: '',
    });
const guardarUbicacion = () => {
    ubicacionForm.post(route('ubi.store'),
        {
            
            onSuccess: () => {
                showModal.value = false;
                ubicacionForm.reset();
            }
        })
}


</script>

<template>

    <Head :title="isUpdating ? 'Editar Libro' : 'Nuevo Libro'" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isUpdating ? `Editar: ${libro.titulo}` : 'Añadir Nuevo Libro' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <form @submit.prevent="actualizar" class="space-y-6">

                            <div class="space-y-2">
                                <label class="block font-bold text-gray-700 ml-1">Título del Libro</label>
                                <Input type="text" v-model="form.titulo" placeholder="Ej: El Quijote"
                                    class="w-full border-gray-200 focus:ring-blue-500 rounded-xl shadow-sm" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Autor</label>
                                    <Input type="text" v-model="form.autor" placeholder="Nombre del autor.."
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Género</label>
                                    <Input type="text" v-model="form.genero"
                                        placeholder="Novela, Terror, Ciencia Ficción.."
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Año de Publicación</label>
                                    <Input type="text" v-model="form.anio" placeholder="2024"
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Editorial</label>
                                    <Input type="text" v-model="form.editorial" placeholder="Editorial.."
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Nº de Páginas</label>
                                    <Input type="number" v-model="form.paginas" placeholder="0"
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Ubicación</label>
                                    <select v-model="form.ubicacion_id"
                                        class="w-full border-gray-200  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  rounded-xl shadow-sm"
                                        @change="nuevaUbicacion">
                                        <option v-for="u in ubicaciones" :key="u.id" :value="u.id">
                                            {{ u.nombre }}
                                        </option>
                                        <option value="nueva">
                                            Añadir nueva ubicación
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!--  -->
                            <div v-if="showModal"
                                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                <div class="bg-white p-6 rounded-lg shadow-xl w-96">
                                    <h3 class="text-lg font-bold mb-4">Nueva Ubicación</h3>

                                    <input v-model="ubicacionForm.nombre" type="text"
                                        placeholder="Nombre de la estantería..." class="w-full border p-2 mb-4" />

                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="showModal = false">Cancelar</button>
                                        <button type="button" @click="guardarUbicacion"
                                            :disabled="ubicacionForm.processing"
                                            class="bg-blue-600 text-white px-4 py-2 rounded">
                                            Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="p-6 bg-blue-50/50 rounded-2xl border-2 border-dashed border-blue-100">
                                <label class="block font-bold text-blue-800 mb-2">Portada del Libro</label>
                                <input type="file" @input="form.portada = $event.target.files[0]" class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-full file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-600 file:text-white
                                              hover:file:bg-blue-700 transition-all cursor-pointer" accept="image/*" />

                                <p v-if="form.errors.portada" class="mt-2 text-red-500 text-sm font-medium italic">
                                    {{ form.errors.portada }}
                                </p>
                            </div>

                            <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                                <button type="submit" :disabled="form.processing"
                                    class="inline-flex items-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-green-100 transition-all active:scale-95 disabled:opacity-50">
                                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    {{ isUpdating ? 'Guardar Cambios' : 'Crear Libro' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>