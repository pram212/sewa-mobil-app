<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import PaginationVue from "@/Components/PaginationLink.vue";
import moment from "moment";
import "moment/locale/id";
moment.locale("id");

const props = defineProps({
    pengembalian: Object,
    requests: Object,
});

const formFilter = useForm({
    search: props.requests.search,
});

function filter() {
    formFilter.get(route("pengembalian.index"), formFilter, {
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
    <Head title="Pengembalian Mobil" />

    <AuthenticatedLayout>
        <template #header><i class="fas fa-folder-open mr-2 capitalize"></i> pengembalian Mobil
        </template>
        <template #breadcrumbs>
            <ul>
                <li>Pengembalian</li>
                <li>Daftar pengembalian</li>
                <li class="font-extrabold">{{ $page.props.auth.user.name }}</li>
            </ul>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-56">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between">
                        <Link :href="route('pengembalian.create')" class="btn btn-sm btn-primary">Entry Pengembalian</Link>
                    </div>
                    
                    <div class="divider"></div>

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
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(sewa, i) in pengembalian.data" :key="i">
                                <td class="capitalize">{{ sewa.merek.nama }}</td>
                                <td>{{ sewa.model }}</td>
                                <td>{{ sewa.plat_nomor }}</td>
                                <td>{{ moment(sewa.pivot.tgl_mulai).format("DD/MM/YYYY") }}</td>
                                <td>{{ moment(sewa.pivot.tgl_selesai).format("DD/MM/YYYY") }}</td>
                                <td>{{ duration(sewa.pivot.tgl_mulai, sewa.pivot.tgl_selesai) }} hari</td>
                                <td>IDR {{ formatPrice(sewa.tarif) }}</td>
                                <td>IDR {{ formatPrice(duration(sewa.pivot.tgl_mulai, sewa.pivot.tgl_selesai) * sewa.tarif) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-between pt-5">
                        <!-- PAGINATION -->
                        <PaginationVue :links="pengembalian.links"></PaginationVue>
                        <!-- PAGINATION END -->

                        <div class="btn-secondary btn btn-sm">
                            Total Data : {{ pengembalian.total }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
