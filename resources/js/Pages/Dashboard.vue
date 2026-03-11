<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import NavLink from '@/Components/NavLink.vue';
import Label from '@/Components/Label.vue';
import Input from '@/Components/Input.vue';
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Maneja tus libros
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                    <!--Enlace a lista libros-->
                        <div><NavLink :href="route('books.libros')" :active="route().current('books.libros')">Lista de Libros</NavLink> </div>
                    <!--Buscar por: -->
                        <div><NavLink :href="route('books.buscar')" :active="route().current('books.buscar')">Buscar Libros</NavLink> </div>
                    <!--Informacion General: -->
                        <div><NavLink :href="route('reseñas.informe')" :active="route().current('reseñas.informe')">Informe General</NavLink> </div>    
                    <!-- Gestion de ubicaciones -->
                        <div><NavLink :href="route('ubi.index')" :active="route().current('ubi.gestion')">Gestionar Ubicaciones</NavLink> </div>    

                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
