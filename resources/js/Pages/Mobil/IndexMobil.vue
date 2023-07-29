<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import PaginationVue from "@/Components/PaginationLink.vue";
import VueFromMultiselect from '@vueform/multiselect';
import './../../../css/tw-vueselect.css';
import moment from "moment";
import "moment/locale/id";
moment.locale("id");



const props = defineProps({
    mobils: Object,
    requests: Object,
    mereks: Object,
});

const formFilter = useForm({
    merek_id: props.requests.merek_id,
    model: props.requests.model,
    tersedia: props.requests.tersedia,
});

function filter() {
    formFilter.get(route("mobil.index"), formFilter, {
        preserveScroll: true,
    });
}

function deleteConfirm(id) {
    swal
        .fire({
            title: "Apakah anda yakin?",
            text: "ingin menghapus data ini!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.isConfirmed) {
                router.delete(route("mobil.destroy", id), {
                    preserveState: false,
                });
            }
        });
}

function formatPrice(value) {
    let val = (value / 1).toFixed(2).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}
</script>

<template>
    <Head title="Manajemen Mobil" />

    <AuthenticatedLayout>
        <template #header><i class="fas fa-folder-open mr-2 capitalize"></i> manajemen mobil
        </template>
        <template #breadcrumbs>
            <ul>
                <li>Manajemen Mobil</li>
                <li>Daftar Mobil</li>
            </ul>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-56">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between">
                        <Link :href="route('mobil.create')" class="btn btn-sm btn-success capitalize">Tambah</Link>
                    </div>
                    <div class="divider">Filter</div>
                    <div class="md:grid md:grid-cols-3 gap-2">
                        <div class="form-control w-full">
                            <label for="induk" class="label">Merek</label>
                            <VueFromMultiselect v-model="formFilter.merek_id" :options="mereks" :searchable="true"
                                :create-option="false" multiselect-blue />
                        </div>

                        <div class="form-control w-full">
                            <label class="label">Model :</label>
                            <input type="text" placeholder="Type here"
                                class="input input-bordered input-primary input-sm w-full" v-model="formFilter.name" />
                        </div>
                        <div class="form-control w-full">
                            <label class="label">Ketersediaan :</label>
                            <VueFromMultiselect v-model="formFilter.tersedia" :options="[
                                { value: 0, label: 'Tidak tersedia' },
                                { value: 1, label: 'Tersedia' },
                            ]" :searchable="true" :create-option="false" multiselect-blue />
                        </div>
                        <div class="grid-cols-span space-x-3 mt-4">
                            <button class="btn btn-sm btn-primary" :class="{ 'opacity-25': formFilter.processing }"
                                :disabled="formFilter.processing" @click="filter()">
                                Search
                            </button>
                            <Link :href="route('mobil.index')" class="btn btn-sm btn-secondary">Clear</Link>
                        </div>

                    </div>


                    <div class="divider"></div>

                    <table class="table table-sm table-zebra">
                        <thead class="uppercase">
                            <tr>
                                <th>Merek</th>
                                <th>Model</th>
                                <th>Plat Nomor</th>
                                <th>Tarif Sewa / hari</th>
                                <th>Tersedia</th>
                                <th class="text-center">opsi</th>
                            </tr>
                        </thead>
                        <tbody class="capitalize">
                            <tr v-for="(mobil, i) in mobils.data" :key="i" :class="{ 'font-extrabold': !mobil.induk }">
                                <td>{{ mobil.merek.nama }}</td>
                                <td>{{ mobil.model }}</td>
                                <td>{{ mobil.plat_nomor }}</td>
                                <td>IDR {{ formatPrice(mobil.tarif) }}</td>
                                <td :class="{ 'text-green-400': mobil.tersedia, 'text-red-400': !mobil.tersedia }">{{
                                    mobil.tersedia ? 'tersedia' : 'tidak tersedia' }}</td>
                                <td class=" text-center">
                                    <Link :href="route('mobil.edit', mobil.id)" class="btn btn-sm btn-info">Edit
                                    </Link>
                                    <button class="btn btn-sm btn-error" @click="deleteConfirm(mobil.id)">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-between pt-5">
                        <!-- PAGINATION -->
                        <PaginationVue :links="mobils.links"></PaginationVue>
                        <!-- PAGINATION END -->

                        <div class="btn-secondary btn btn-sm">
                            Total Data : {{ mobils.total }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
