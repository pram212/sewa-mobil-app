<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import VueFromMultiselect from '@vueform/multiselect';
import { ref } from 'vue';
// import '@vueform/multiselect/themes/default.css';
import './../../../css/tw-vueselect.css';
import CurrencyInputVue from '@/Components/CurrencyInput.vue';

const props = defineProps({
    mereks: Object,
    mobils: Object,
    requests: Object,
});


const formFilter = useForm({
    tgl_mulai: props.requests.tgl_mulai,
    tgl_selesai: props.requests.tgl_selesai,
    merek_id: props.requests.merek_id,
})

function filter() {
    formFilter.get(route("penyewaan.create"), formFilter, {
        preserveScroll: true,
    });
}

function formatPrice(value) {
    let val = (value / 1).toFixed(2).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

</script>

<template>
    <AuthenticatedLayout>

        <Head title="Penyewaan Mobil" />

        <template #header><i class="fas fa-area-car mr-2"></i> penyewaan baru</template>
        <template #breadcrumbs>
            <ul>
                <li>Penyewaan Mobil</li>
                <li>Penyewaan Baru</li>
            </ul>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-40">
            <div class="bg-base-100 overflow-visible shadow-sm sm:rounded-lg p-5 z-0">
                <form @submit.prevent="filter" class="lg:grid lg:grid-cols-2 gap-2">
                    <div class="form-control">
                        <label for="" class="label">Tanggal selesai :</label>
                        <input type="date" v-model="formFilter.tgl_mulai" class="input input-sm input-primary">
                    </div>

                    <div class="form-control">
                        <label for="" class="label">Tanggal selesai :</label>
                        <input type="date" v-model="formFilter.tgl_selesai" class="input input-sm input-primary">
                    </div>

                    <div class="form-control lg:col-span-2">
                        <label for="merek_id" class="label">Merek Mobil :</label>
                        <VueFromMultiselect v-model="formFilter.merek_id" :options="mereks" :searchable="true"
                            :create-option="false" multiselect-blue />
                        <InputError class="mt-2" :message="formFilter.errors.merek_id" />
                    </div>

                    <div class="flex justify-center lg:col-span-2 space-x-3 mt-4">
                        <button class="btn btn-sm btn-primary" :class="{ 'opacity-25': formFilter.processing }"
                            :disabled="formFilter.processing">
                            Cek Ketersediaan
                        </button>
                        <Link :href="route('penyewaan.create')" class="btn btn-sm btn-secondary">Reset</Link>
                    </div>
                </form>
                <div class="divider uppercase">Mobil yang tersedia</div>

                <table class="table table-sm table-zebra">
                    <thead class="uppercase">
                        <tr>
                            <th>Merek</th>
                            <th>Model</th>
                            <th>Plat Nomor</th>
                            <th>Tarif Sewa / hari</th>
                            <th class="text-center">opsi</th>
                        </tr>
                    </thead>
                    <tbody class="capitalize">
                        <tr v-for="(mobil, i) in mobils" :key="i">
                            <td>{{ mobil.merek.nama }}</td>
                            <td>{{ mobil.model }}</td>
                            <td>{{ mobil.plat_nomor }}</td>
                            <td>IDR {{ formatPrice(mobil.tarif) }}</td>
                            <td class=" text-center">
                                <Link method="post" as="button" :href="route('penyewaan.store')"
                                    :data="{ tgl_mulai: formFilter.tgl_mulai, tgl_selesai: formFilter.tgl_selesai, mobil_id: mobil.id }"
                                    class="btn btn-sm btn-info">Checkout
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
</AuthenticatedLayout></template>