<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2';
import { computed } from 'vue';

const props = defineProps({
    ubicaciones: Array
})

const ubicacionesFiltradas = computed(() => {
    if (!Array.isArray(props.ubicaciones)) {
        return [];
    }

    return props.ubicaciones.slice(1);
});

const eliminarUbicacion = (id, nombre) => {
    Swal.fire({
        title: "Estas seguro?",
        text: `Vas a eliminar la ubicacion "${nombre}". ¡Esta acción no se puede deshacer!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3b82f6",
        cancelButtoncolor: "#ef4444",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route("ubi.destroy", id), {
                onSuccess: () => {
                    Swal.fire(
                        "¡Eliminado!",
                        "La ubicacion ha sido borrada correctamente.",
                        "success",
                    );
                },
                onError: () => {
                    Swal.fire(
                        "Error",
                        "No se pudo eliminar la ubicacion.",
                        "error",
                    );
                },
            });
        }
    });
};

</script>

<template>

    <Head title="Lista Ubicaciones" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lista de ubicaciones
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ul v-for="ubicacion in ubicacionesFiltradas" :key="ubicacion.id">
                            <li>
                                <strong>Nombre:</strong> {{ ubicacion.nombre }}
                            </li>
                            <button @click="
                                eliminarUbicacion(
                                    ubicacion.id,
                                    ubicacion.nombre,
                                )
                                "
                                class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-bold transition-colors">
                                Eliminar
                            </button>
                            <br>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
