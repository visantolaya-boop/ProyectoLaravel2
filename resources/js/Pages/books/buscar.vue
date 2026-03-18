<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm, usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import NavLink from "@/Components/NavLink.vue";
import Input from "@/Components/Input.vue";
import { nextTick, ref, watch } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Swal from "sweetalert2";
import StarIcon from "@/Components/StarIcon.vue";

const props = defineProps({
    libros: Object,
    filters: {
        type: Object,
        default: () => ({}),
    },
    ubicaciones: {
        type: Array,
        default: () => ([]),
    },
    generos: {
        type: Array,
        default: () => ([]),
    },
});

let search = ref(props.filters?.search || ""),
    filterEstado = ref(''),
    filterPuntuacion = ref(''),
    filterUbicacion = ref(''),
    filterGenero = ref('');

watch(
    [search, filterEstado, filterPuntuacion, filterUbicacion, filterGenero],
    ([s, e, p, u, g], [oldS, oldE, oldP, oldU, oldG]) => {

        if (u === 'nueva') {
            filterUbicacion.value = oldU;
            return;
        }

        if (g === 'nuevo') {
            filterGenero.value = oldG;
            return;
        }

        Inertia.get(
            route("books.buscar"),
            { search: s, estado: e, puntuacion: p, ubicacion: u, genero: g },
            { preserveState: true, replace: true, preserveScroll: true }
        );
    },
    { deep: true }
);


const resetFiltros = () => {
    search.value = "";
    filterEstado.value = "";
    filterPuntuacion.value = "";
    filterUbicacion.value = "";
    filterGenero.value = "";

    Inertia.get(route("books.buscar")), {}, {
        preserveState: true,
        replace: true,
    }
}
const eliminarLibro = (id, titulo) => {
    Swal.fire({
        title: "Estas seguro?",
        text: `Vas a eliminar el libro "${titulo}". ¡Esta acción no se puede deshacer!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3b82f6",
        cancelButtoncolor: "#ef4444",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route("books.destroy", id), {
                onSuccess: () => {
                    Swal.fire(
                        "¡Eliminado!",
                        "El libro ha sido borrado correctamente.",
                        "success",
                    );
                },
                onError: () => {
                    Swal.fire(
                        "Error",
                        "No se pudo eliminar el libro.",
                        "error",
                    );
                },
            });
        }
    });
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
                ubicacionForm.reset();
            }
        })
}

const guardarGenero = () => {
    generoForm.post(route('genero.store'),
        {
            onSuccess: () => {
                showModal2.value = false;
                generoForm.reset();
            }
        })
}


</script>

<template>

    <Head title="Búsqueda" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Búsqueda personalizada de libros
            </h2>
        </template>

        <div class="py-6 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b border-gray-200">

                    <div class="p-4 md:p-6 bg-white">
                        <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
                            <form @submit.prevent class="space-y-6">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="relative flex-grow group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                            </svg>
                                        </div>
                                        <Input v-model="search" placeholder="Título, autor..."
                                            class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 rounded-xl transition-all text-sm" />
                                    </div>
                                    <NavLink :href="route('books.create')"
                                        class="w-full md:w-auto inline-flex justify-center items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-md active:scale-95 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Nuevo Libro</span>
                                    </NavLink>
                                </div>

                                <div class="pt-4 border-t border-gray-50">
                                    <p class="text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">Filtrar
                                        por:</p>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                        <div
                                            class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 focus-within:ring-2 focus-within:ring-blue-100">
                                            <select v-model="filterEstado"
                                                class="w-full bg-transparent border-none focus:ring-0 text-sm py-2 cursor-pointer">
                                                <option value="">Cualquier Estado</option>
                                                <option value="Pendiente">Pendiente</option>
                                                <option value="Leyendo">Leyendo</option>
                                                <option value="Terminado">Terminado</option>
                                                <option value="Abandonado">Abandonado</option>
                                            </select>
                                        </div>

                                        <div
                                            class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 focus-within:ring-2 focus-within:ring-blue-100">
                                            <StarIcon class="text-green-400 w-4 h-4" />
                                            <select v-model="filterPuntuacion"
                                                class="w-full bg-transparent border-none focus:ring-0 text-sm py-2 cursor-pointer">
                                                <option value="">Cualquier Puntuación</option>
                                                <option v-for="n in [5, 4, 3, 2, 1, 0]" :key="n" :value="n">{{ n }} Estrellas
                                                </option>
                                            </select>
                                        </div>

                                        <div
                                            class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 focus-within:ring-2 focus-within:ring-blue-100">
                                            <select v-model="filterUbicacion"
                                                class="w-full bg-transparent border-none focus:ring-0 text-sm py-2 cursor-pointer"
                                                @change="nuevaUbicacion">
                                                <option value="">Ubicación</option>
                                                <option v-for="u in props.ubicaciones" :key="u.id" :value="u.nombre">{{
                                                    u.nombre
                                                    }}</option>
                                                <option value="nueva" class="text-blue-600 font-bold">+ Nueva ubicación
                                                </option>
                                            </select>
                                        </div>

                                        <div
                                            class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 focus-within:ring-2 focus-within:ring-blue-100">
                                            <select v-model="filterGenero"
                                                class="w-full bg-transparent border-none focus:ring-0 text-sm py-2 cursor-pointer"
                                                @change="nuevoGenero">
                                                <option value="">Género</option>
                                                <option v-for="g in props.generos" :key="g.id" :value="g.nombre">{{
                                                    g.nombre }}
                                                </option>
                                                <option value="nuevo" class="text-blue-600 font-bold">+ Nuevo género
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex justify-end">
                                        <button @click="resetFiltros" type="button"
                                            class="text-xs font-semibold text-blue-600 hover:text-blue-800 underline transition">
                                            Limpiar todos los filtros
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div v-for="libro in libros.data" :key="libro.id"
                                class="shadow-sm hover:shadow-md transition-shadow p-4 md:p-5 bg-white rounded-2xl flex flex-col sm:flex-row items-center sm:items-start gap-4 border border-gray-100">

                                <div class="flex-shrink-0">
                                    <img :src="libro.portada ? '/storage/' + libro.portada : '/storage/imagenes/default_book.jpg'"
                                        :alt="libro.titulo"
                                        class="w-32 h-48 sm:w-28 sm:h-40 object-cover rounded-xl shadow-sm" />
                                </div>

                                <div class="flex-grow flex flex-col w-full">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-bold text-gray-800 leading-tight mb-1">{{ libro.titulo
                                            }}</h3>
                                    </div>

                                    <div class="grid grid-cols-2 gap-x-2 gap-y-1 mb-3">
                                        <p class="text-xs text-gray-500"><strong>Autor:</strong> {{ libro.autor }}</p>
                                        <p class="text-xs text-gray-500"><strong>Año:</strong> {{ libro.anio }}</p>
                                        <p class="text-xs text-gray-500"><strong>Género:</strong> {{ libro.genero.nombre
                                            }}</p>
                                        <p class="text-xs text-gray-500"><strong>Estado:</strong>
                                            <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-medium"
                                                style="font-size: 10px;">
                                                {{ libro.lecturas?.estado || 'Sin registrar' }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-1 mb-4">
                                        <div class="flex">
                                            <StarIcon v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= (libro.lecturas?.puntuacion || 0)
                                                ? 'text-green-400 fill-current'
                                                : 'text-gray-200'" />
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 mt-auto border-t border-gray-50 pt-3">
                                        <NavLink :href="route('books.reseña', libro.lecturas?.id || 0)"
                                            class="text-xs font-bold text-blue-600 px-3 py-2 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                            Ver Reseña
                                        </NavLink>
                                        <NavLink :href="route('books.editar', libro.id)"
                                            class="text-xs font-bold text-gray-700 px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                            Editar
                                        </NavLink>
                                        <button @click="eliminarLibro(libro.id, libro.titulo)"
                                            class="text-xs font-bold text-white px-3 py-2 bg-red-500 rounded-lg hover:bg-red-600 transition-colors ml-auto">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex justify-center">
                            <Pagination :links="libros.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showModal || showModal2"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 text-sm">
            <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">{{ showModal ? 'Nueva Ubicación' : 'Nuevo Género' }}</h3>
                <input v-if="showModal" v-model="ubicacionForm.nombre" ref="inputNombreUbicacion" type="text"
                    placeholder="Nombre..." class="w-full border-gray-200 rounded-xl mb-2" />
                <input v-else v-model="generoForm.nombre" ref="inputNombreGenero" type="text" placeholder="Nombre..."
                    class="w-full border-gray-200 rounded-xl mb-2" />

                <p v-if="(showModal ? ubicacionForm : generoForm).errors.nombre"
                    class="text-red-500 text-xs mb-4 italic">
                    {{ (showModal ? ubicacionForm : generoForm).errors.nombre }}
                </p>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" class="px-4 py-2 text-gray-500 font-medium"
                        @click="showModal = false; showModal2 = false">Cancelar</button>
                    <button type="button" @click="showModal ? guardarUbicacion() : guardarGenero()"
                        class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-700 transition-colors">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
