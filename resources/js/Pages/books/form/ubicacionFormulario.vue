<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Input from '@/Components/Input.vue';

const props = defineProps({
    ubicacion: Object
});

const form = useForm({
    nombre: props.ubicacion.nombre || '',

});

const actualizar = () => {
    form.post(route('ubi.store'));
};
</script>

<template>

    <Head title="Añadir Ubicacion" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Añadir Ubicacion
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <form @submit.prevent="actualizar" class="space-y-6">

                            <div class="space-y-2">
                                <label class="block font-bold text-gray-700 ml-1">Nombre de Ubicacion</label>
                                <Input type="text" v-model="form.nombre" placeholder="Ej: Salon"
                                    class="w-full border-gray-200 focus:ring-blue-500 rounded-xl shadow-sm" />
                                <div v-if="form.errors.nombre" class="text-red-500 text-sm mt-1 ml-1">
                                    {{ form.errors.nombre }}
                                </div>
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
                                    Crear Ubicacion
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>