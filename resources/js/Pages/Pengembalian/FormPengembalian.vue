<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import VueFromMultiselect from '@vueform/multiselect';
import { ref } from 'vue';
// import '@vueform/multiselect/themes/default.css';
import './../../../css/tw-vueselect.css';
import CurrencyInputVue from '@/Components/CurrencyInput.vue';
import moment from "moment";
import "moment/locale/id";
moment.locale("id");

const props = defineProps({
    mereks: Object,
    penyewaan: Object,
    requests: Object,
});


const formFilter = useForm({
    plat_nomor: props.requests.plat_nomor,
})

function filter() {
    formFilter.get(route("pengembalian.create"), formFilter, {
        preserveScroll: true,
    });
}

function formatPrice(value) {
    let val = (value / 1).toFixed(2).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}


function duration(startdate, enddate) {
    var start = moment(startdate, "YYYY-MM-DD");
    var end = moment(enddate, "YYYY-MM-DD");

    //Difference in number of days
    return moment.duration(end.diff(start)).asDays() + 1;
}

</script>

<template>
    <AuthenticatedLayout>

        <Head title="Pengembalian Mobil" />

        <template #header><i class="fas fa-area-car mr-2"></i> pengembalian baru</template>
        <template #breadcrumbs>
            <ul>
                <li>Pengembalian Mobil</li>
                <li>Entry Pengembalian</li>
            </ul>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-40">
            <div class="bg-base-100 overflow-visible shadow-sm sm:rounded-lg p-5 z-0">
                <form @submit.prevent="filter">
                    <div class="form-control">
                        <label for="" class="label">Plat Nomor :</label>
                        <input type="text" v-model="formFilter.plat_nomor" class="input input-sm input-primary">
                    </div>

                    <div class="flex justify-center lg:col-span-2 space-x-3 mt-4">
                        <button class="btn btn-sm btn-primary" :class="{ 'opacity-25': formFilter.processing }"
                            :disabled="formFilter.processing">
                            Cek Penyewaan
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
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Durasi</th>
                            <th>Tarif Sewa / Hari</th>
                            <th>Total Tagihan</th>
                            <th class="text-center">opsi</th>
                        </tr>
                    </thead>
                    <tbody class="capitalize">
                        <tr v-for="(sewa, i) in penyewaan" :key="i">
                            <td>{{ sewa.mobil.merek.nama }}</td>
                            <td>{{ sewa.mobil.model }}</td>
                            <td>{{ sewa.mobil.plat_nomor }}</td>
                            <td>{{ moment(sewa.tgl_mulai).format("DD/MM/YYYY") }}</td>
                            <td>{{ moment(sewa.tgl_selesai).format("DD/MM/YYYY") }}</td>
                            <td>{{ duration(sewa.tgl_mulai, sewa.tgl_selesai) }} hari</td>
                            <td>IDR {{ formatPrice(sewa.mobil.tarif) }}</td>
                            <td>IDR {{ formatPrice(duration(sewa.tgl_mulai, sewa.tgl_selesai) * sewa.mobil.tarif) }}</td>
                            <td class=" text-center">
                                <Link method="post" as="button" :href="route('pengembalian.store')"
                                    :data="{ mobil_id: sewa.mobil_id, tgl_mulai: sewa.tgl_mulai, tgl_selesai : sewa.tgl_selesai }"
                                    class="btn btn-sm btn-info">Kembalikan
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
</AuthenticatedLayout></template>