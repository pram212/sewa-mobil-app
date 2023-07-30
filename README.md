
## Tentang Aplikasi

Aplikasi penyewaan mobil yang dibuat dengan laravel guna memenuhi tes teknis seleksi karyawan. saya buat dengan model SPA (Single Page Application)

## Pre Requisite

Sebelum anda melakukan instalasi pada komputer Anda pastikan bahwa pada komputer anda sudah terinstall:
- php >= 8.1*
- composer
- git
- node.js

## Instalasi

- clone repository
- cd repository (command)
- composer install (command) unuk menginstall dependencies php
- cp .env.example .env untuk membuat file .env yang dicopy dari file .env.example 
- atur konfigurasi database di file .env sesuai dengan yang ada di komputer
- php artisan migrate --seed untuk membuat skema database dan memuat data dummy
- npm install
- npm run build
- php artisan serve 
- buka link http://localhost:8000

Jika Anda ingin melihat secara langsung preview aplikasi tanpa harus instalasi di komputer Anda, silahkan coba di server pribadi saya di alamat https://sewamobil.monoport.web.id.
## Teknologi
- PHP
- Mysql
- Laravel
- Html, Css, Javascript
- Vue.js
- Tailwind css
- Inertia.js
