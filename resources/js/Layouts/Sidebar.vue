<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, onMounted } from 'vue'

const menus = [
    {
        name: "dashboard",
        url: "/dashboard",
        icon: '<i class="fas fa-area-chart w-5"></i>',
    },
    {
        name: "dewan pengawas",
        url: "#",
        icon: '<i class="fas fa-users w-5"></i>',
    },
    {
        name: "komite",
        url: "/komite",
        icon: '<i class="fas fa-id-badge w-5 w-5"></i>',
    },
    {
        name: "arsip",
        url: "/arsip",
        icon: '<i class="fas fa-folder w-5"></i>',
    },
    {
        name: "laporan",
        url: "/laporan",
        icon: '<i class="fas fa-book w-5"></i>',
        submenu: [
            {
                name: "laporan dewas",
                url: "/laporan",
            },
            {
                name: "laporan arsip",
                url: "/laporan",
            },
            {
                name: "laporan komite",
                url: "/laporan",
            },

        ]
    },
    {
        name: "pengguna",
        url: "/admin/user",
        icon: '<i class="fas fa-key w-5"></i>',
    },
    {
        name: "data master",
        url: "/master",
        icon: '<i class="fas fa-database w-5"></i>',
        submenu: [
            {
                name: "menu dewas",
                url: "/master/menu-dewas",
            },
            {
                name: "jenis dokumen",
                url: "/master/jenis-dokumen",
            },
            {
                name: "kondisi dokumen",
                url: "/master/kondisi-dokumen",
            },
            {
                name: "media dokumen",
                url: "/master/media-dokumen",
            },
            {
                name: "kategori dokumen",
                url: "/master/kategori-dokumen",
            },
            {
                name: "jenis agenda",
                url: "/master/jenis-agenda",
            },
            {
                name: "kategori tkt. perkembangan",
                url: "/master/tkt-perkembangan",
            },
            {
                name: "lokasi",
                url: "/master/lokasi",
                submenu: [
                    {
                        name: "gedung",
                        url: "/master/lokasi-gedung",
                    },
                    {
                        name: "lemari",
                        url: "/master/lokasi-lemari",
                    },
                    {
                        name: "baris",
                        url: "/master/lokasi-baris",
                    },
                ]
            },
        ]
    }
];


const isEmtySubmenu = (submenu) => {
    return _.size(submenu) > 0 ? true : false
}

</script>

<template>
    <aside class="bg-base-100 w-80 h-full">
        <ul class="menu p-4 w-80 capitalize font-extrabold bg-base-100 min-h-screen text-base-content">
            
            <div class="divider my-0"></div>
            <h2 class="menu-title">SEWA MOBIL APP</h2>
            <li v-for="(menu, i) in menus" :key="i">
                <!-- submenu 1 -->
                <details v-if="isEmtySubmenu(menu.submenu)" :open="$page.url.includes(menu.url)">
                    <summary>
                        <span v-html="menu.icon"></span> {{ menu.name }}
                    </summary>
                    <ul>
                        <li v-for="(submenu, j) in menu.submenu" :key="j">
                            <details v-if="isEmtySubmenu(submenu.submenu)" :open="$page.url.includes(submenu.url)">
                                <summary>
                                    <i class="fas fa-folder-open"></i> {{ submenu.name }}
                                </summary>
                                <ul>
                                    <li v-for="(submenu2, j) in submenu.submenu" :key="j">
                                        <Link :href="submenu2.url" :class="{ active: $page.url.includes(submenu2.url) }"><i
                                            class="fas fa-folder-open"></i>
                                        {{ submenu2.name }}</Link>
                                    </li>
                                </ul>
                            </details>

                            <Link v-else :href="submenu.url" :class="{ active: $page.url.includes(submenu.url) }"><i
                                class="fas fa-folder-open"></i>
                            {{ submenu.name }}</Link>
                        </li>
                    </ul>
                </details>
                <!-- submenu 1 end -->

                <Link v-else :href="menu.url" :class="{ active: $page.url.includes(menu.url) }">
                <span v-html="menu.icon"></span> {{ menu.name }}</Link>
            </li>
            <li class="my-5"></li>
        </ul>
    </aside>

    <!-- </aside> -->
</template>
