<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2';
import { computed, nextTick } from 'vue';
import { ref } from 'vue';


const props = defineProps({
    ubicaciones: Array
})

const ubicacionesFiltradas = computed(() => {
    return Array.isArray(props.ubicaciones) ? props.ubicaciones.slice(1) : [];
});

const eliminarUbicacion = (id, nombre) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: `Vas a eliminar "${nombre}". Esta acción no se puede deshacer.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444", 
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route("ubi.destroy", id), {
                onSuccess: () => {
                    Swal.fire("¡Eliminado!", "La ubicación ha sido borrada.", "success");
                },
                onError: () => {
                    Swal.fire("Error", "No se pudo eliminar la ubicación.", "error");
                },
            });
        }
    });
};

const inputNombreUbicacion = ref(null);

const nuevaUbicacion = () => {
    showModal.value = true;
    nextTick(() => {
        if (inputNombreUbicacion.value) {
            inputNombreUbicacion.value.focus();
        }
    });
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

    <Head title="Lista Ubicaciones" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Ubicaciones
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 bg-white">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Nombre de la Ubicación
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <!-- Añadir Ubicacion -->
                                    <tr class="hover:bg-gray-50 transition-colors">

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-8 w-8 bg-green-100  rounded-full flex items-center justify-center">

                                                </div>
                                                <span class="ml-4 text-sm font-medium text-gray-900">
                                                    Añadir Nueva Ubicación
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="nuevaUbicacion"
                                                class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 hover:bg-green-600 hover:text-white rounded-xl transition-all duration-300 group shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                <span class="font-bold">Añadir</span>
                                            </button>
                                        </td>
                                    </tr>
                                    <!--  -->
                                    <div v-if="showModal"
                                        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                        <div class="bg-white p-6 rounded-lg shadow-xl w-96">
                                            <h3 class="text-lg font-bold mb-4">Nueva Ubicación</h3>

                                            <input v-model="ubicacionForm.nombre" ref="inputNombreUbicacion" type="text"
                                                placeholder="Nombre de la ubicacion..." class="w-full border p-2 mb-4"
                                                @input="ubicacionForm.clearErrors('nombre')" />

                                            <p v-if="ubicacionForm.errors.nombre"
                                                class="text-red-500 text-xs mt-1 italic">
                                                {{ ubicacionForm.errors.nombre }}
                                            </p>

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
                                    <!-- Gestion + Eliminar -->
                                    <tr v-for="ubicacion in ubicacionesFiltradas" :key="ubicacion.id"
                                        class="hover:bg-gray-50 transition-colors">

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-8 w-8 bg-orange-100  rounded-full flex items-center justify-center">

                                                </div>
                                                <span class="ml-4 text-sm font-medium text-gray-900">
                                                    {{ ubicacion.nombre }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="eliminarUbicacion(ubicacion.id, ubicacion.nombre)"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all duration-200 group">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="ubicacionesFiltradas.length === 0">
                                        <td colspan="2" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                            No hay ubicaciones registradas para mostrar.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </BreezeAuthenticatedLayout>
</template>