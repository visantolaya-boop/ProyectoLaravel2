<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Input from '@/Components/Input.vue';
import Label from '@/Components/Label.vue'; // Usa <label class="font-bold text-gray-700"> si no tienes el componente
import { nextTick, ref } from 'vue';

const props = defineProps({
    libro: Object,
    isUpdating: Boolean,
    ubicaciones: {
        type: Array,
        default: () => ([]),
    },
    generos: {
        type: Array,
        default: () => ([]),
    }
});

const form = useForm({
    _method: props.isUpdating ? 'put' : 'post',
    titulo: props.libro.titulo || '',
    autor: props.libro.autor || '',
    anio: props.libro.anio || '',
    editorial: props.libro.editorial || '',
    paginas: props.libro.paginas || '',
    genero_id: props.libro.genero_id ?? '',
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

const inputNombreUbicacion = ref(null);

const nuevaUbicacion = (event) => {
    if (event.target.value === 'nueva') {
        showModal.value = true;
        nextTick(() => {
            if (inputNombreUbicacion.value) {
                inputNombreUbicacion.value.focus();
            }
        });
    }
}

const inputNombreGenero = ref(null);

const nuevoGenero = (event) => {
    if (event.target.value === 'nuevo') {
        showModal2.value = true;
        nextTick(() => {
            if (inputNombreGenero.value)
                inputNombreGenero.value.focus();
        });
    }
}

const showModal = ref(false),
    ubicacionForm = useForm({
        nombre: '',
    });


const showModal2 = ref(false),
    generoForm = useForm({
        nombre: '',
    });

const guardarUbicacion = () => {
    ubicacionForm.post(route('ubi.store'),
        {

            onSuccess: () => {
                showModal.value = false;
                form.ubicacion_id = '';
                ubicacionForm.reset();
            }
        })
}

const guardarGenero = () => {
    generoForm.post(route('genero.store'),
        {

            onSuccess: () => {
                showModal2.value = false;
                form.genero_id = '';
                generoForm.reset();
            }
        })
}


const volverAtras = () => {
    window.history.back();
};


</script>

<template>
    <Head :title="isUpdating ? 'Editar Libro' : 'Nuevo Libro'" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight px-4 sm:px-0">
                {{ isUpdating ? `Editar: ${libro.titulo}` : 'Añadir Nuevo Libro' }}
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-5 sm:p-8">
                        <form @submit.prevent="actualizar" @keydown.enter.prevent class="space-y-6">

                            <div class="space-y-2">
                                <label class="block font-bold text-gray-700 ml-1 text-sm sm:text-base">Título del Libro</label>
                                <Input type="text" v-model="form.titulo" placeholder="Ej: El Quijote"
                                    class="w-full border-gray-200 focus:ring-blue-500 rounded-xl shadow-sm" />
                                <div v-if="form.errors.titulo" class="text-red-400 text-sm italic">{{ form.errors.titulo }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1 text-sm sm:text-base">Autor</label>
                                    <Input type="text" v-model="form.autor" placeholder="Nombre del autor.."
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                    <div v-if="form.errors.autor" class="text-red-400 text-sm italic">{{ form.errors.autor }}</div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1 text-sm sm:text-base">Género</label>
                                    <select v-model="form.genero_id"
                                        class="w-full border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-xl shadow-sm"
                                        @change="nuevoGenero">
                                        <option v-for="g in generos" :key="g.id" :value="g.id">{{ g.nombre }}</option>
                                        <option value="nuevo" class="font-bold text-blue-600">+ Añadir nuevo género</option>
                                    </select>
                                </div>

                                <div class="space-y-2 sm:col-span-2 md:col-span-1">
                                    <label class="block font-bold text-gray-700 ml-1 text-sm sm:text-base">Año de Publicación</label>
                                    <Input type="text" v-model="form.anio" placeholder="2024"
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                    <div v-if="form.errors.anio" class="text-red-400 text-sm italic">{{ form.errors.anio }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1 text-sm sm:text-base">Editorial</label>
                                    <Input type="text" v-model="form.editorial" placeholder="Editorial.."
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                    <div v-if="form.errors.editorial" class="text-red-400 text-sm italic">{{ form.errors.editorial }}</div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1 text-sm sm:text-base">Nº de Páginas</label>
                                    <Input type="number" v-model="form.paginas" placeholder="0"
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                    <div v-if="form.errors.paginas" class="text-red-400 text-sm italic">{{ form.errors.paginas }}</div>
                                </div>

                                <div class="space-y-2 sm:col-span-2 md:col-span-1">
                                    <label class="block font-bold text-gray-700 ml-1 text-sm sm:text-base">Ubicación</label>
                                    <select v-model="form.ubicacion_id"
                                        class="w-full border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-xl shadow-sm"
                                        @change="nuevaUbicacion">
                                        <option v-for="u in ubicaciones" :key="u.id" :value="u.id">{{ u.nombre }}</option>
                                        <option value="nueva" class="font-bold text-blue-600">+ Añadir nueva ubicación</option>
                                    </select>
                                </div>
                            </div>

                            <div class="p-4 sm:p-6 bg-blue-50/50 rounded-2xl border-2 border-dashed border-blue-100">
                                <label class="block font-bold text-blue-800 mb-2 text-sm sm:text-base">Portada del Libro</label>
                                <input type="file" @input="form.portada = $event.target.files[0]" 
                                    class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-xs sm:file:text-sm file:font-semibold
                                    file:bg-blue-600 file:text-white
                                    hover:file:bg-blue-700 transition-all cursor-pointer" 
                                    accept="image/*" />
                                <p v-if="form.errors.portada" class="mt-2 text-red-500 text-xs sm:text-sm font-medium italic">
                                    {{ form.errors.portada }}
                                </p>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                                <button type="button" @click="volverAtras"
                                    class="w-full sm:w-auto px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold uppercase tracking-widest rounded-xl transition-all active:scale-95 text-center">
                                    Cancelar
                                </button>
                                <button type="submit" :disabled="form.processing"
                                    class="w-full sm:w-auto px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-green-100 transition-all active:scale-95 disabled:opacity-50 text-center">
                                    {{ isUpdating ? 'Guardar Cambios' : 'Crear Libro' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showModal || showModal2" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-sm">
                <h3 class="text-lg font-bold mb-4 text-gray-800">
                    {{ showModal ? 'Nueva Ubicación' : 'Nuevo Género' }}
                </h3>

                <input v-if="showModal" v-model="ubicacionForm.nombre" ref="inputNombreUbicacion" type="text"
                    placeholder="Nombre..." class="w-full border border-gray-200 rounded-xl p-3 mb-2 focus:ring-2 focus:ring-blue-500 outline-none"
                    @keyup.enter="guardarUbicacion" />
                
                <input v-if="showModal2" v-model="generoForm.nombre" ref="inputNombreGenero" type="text"
                    placeholder="Nombre..." class="w-full border border-gray-200 rounded-xl p-3 mb-2 focus:ring-2 focus:ring-blue-500 outline-none"
                    @keyup.enter="guardarGenero" />

                <p v-if="ubicacionForm.errors.nombre || generoForm.errors.nombre" class="text-red-500 text-xs mb-4 italic">
                    {{ ubicacionForm.errors.nombre || generoForm.errors.nombre }}
                </p>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" class="text-gray-500 font-semibold px-4 py-2" 
                        @click="showModal = false; showModal2 = false">Cancelar</button>
                    <button type="button" @click="showModal ? guardarUbicacion() : guardarGenero()"
                        class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold shadow-md hover:bg-blue-700 transition-all">
                        Guardar
                    </button>
                </div>
            </div>
        </div>

    </BreezeAuthenticatedLayout>
</template>