<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import PaginationVue from "@/Components/PaginationLink.vue";
import moment from "moment";
import "moment/locale/id";
moment.locale("id");

const props = defineProps({
    penyewaan: Object,
    requests: Object,
});

const formFilter = useForm({
    search: props.requests.search,
});

function filter() {
    formFilter.get(route("penyewaan.index"), formFilter, {
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
    <Head title="Penyewaan Mobil" />

    <AuthenticatedLayout>
        <template #header><i class="fas fa-folder-open mr-2 capitalize"></i> Penyewaan Mobil
        </template>
        <template #breadcrumbs>
            <ul>
                <li>Penyewaan</li>
                <li>Daftar Penyewaan</li>
                <li class="font-extrabold">{{ $page.props.auth.user.name }}</li>
            </ul>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-56">
            <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between">
                        <Link :href="route('penyewaan.create')" class="btn btn-sm btn-primary">Sewa Baru</Link>
                        <input type="text" v-model="formFilter.search" class="input input-sm" placeholder="ketik + enter" @keyup.enter="filter" autofocus>
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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(sewa, i) in penyewaan.data" :key="i">
                                <td class="capitalize">{{ sewa.merek.nama }}</td>
                                <td>{{ sewa.model }}</td>
                                <td>{{ sewa.plat_nomor }}</td>
                                <td>{{ moment(sewa.pivot.tgl_mulai).format("DD/MM/YYYY") }}</td>
                                <td>{{ moment(sewa.pivot.tgl_selesai).format("DD/MM/YYYY") }}</td>
                                <td>{{ duration(sewa.pivot.tgl_mulai, sewa.pivot.tgl_selesai) }} hari</td>
                                <td>IDR {{ formatPrice(sewa.tarif) }}</td>
                                <td>IDR {{ formatPrice(duration(sewa.pivot.tgl_mulai, sewa.pivot.tgl_selesai) * sewa.tarif) }}</td>
                                <td>
                                    <span :class="{'badge badge-success' : sewa.pivot.status, 'badge badge-error' : !sewa.pivot.status,  }">
                                        {{ sewa.pivot.status ? 'sedang berlangsung' : 'selesai' }}
                                    </span>
                            </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-between pt-5">
                        <!-- PAGINATION -->
                        <PaginationVue :links="penyewaan.links"></PaginationVue>
                        <!-- PAGINATION END -->

                        <div class="btn-secondary btn btn-sm">
                            Total Data : {{ penyewaan.total }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
