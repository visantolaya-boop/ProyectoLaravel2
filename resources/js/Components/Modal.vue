<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    titulo: String,
    ruta: String,       // Ej: 'ubi.store'
    placeholder: String
});

const emit = defineEmits(['cerrado']);
const showModal = ref(false);

const form = useForm({
    nombre: '',
});

// Metodos que el padre podrá activar
const abrir = () => showModal.value = true;
const cerrar = () => {
    showModal.value = false;
    form.reset();
    emit('cerrado');
};

const guardar = () => {
    form.post(route(props.ruta), {
        onSuccess: () => cerrar(),
    });
};

// Importante: esto permite que el padre use "modalRef.value.abrir()"
defineExpose({ abrir });
</script>

<template>
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-96 text-black">
            <h3 class="text-lg font-bold mb-4">{{ titulo }}</h3>

            <input v-model="form.nombre" type="text" :placeholder="placeholder"
                   class="w-full border p-2 mb-4 rounded" @keyup.enter="guardar" />

            <div v-if="form.errors.nombre" class="text-red-500 text-sm mb-2">{{ form.errors.nombre }}</div>

            <div class="flex justify-end gap-2">
                <button type="button" @click="cerrar" class="hover:underline">Cancelar</button>
                <button type="button" @click="guardar" :disabled="form.processing"
                        class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50">
                    {{ form.processing ? 'Guardando...' : 'Guardar' }}
                </button>
            </div>
        </div>
    </div>
</template>