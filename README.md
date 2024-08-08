# Energeek Fullstack Test

`demo url: ` [https://energeek-fullstack.acielana.my.id](https://energeek-fullstack.acielana.my.id)

## Tech Stack
1. Laravel 10
2. PHP v8.2
3. Composer v2.7.1

## Instalasi
1. Buka Terminal. (ex: git bash, cmd, dll)
2. Clone repository ini menggunakan perintah `git clone https://github.com/ariaschecter/energeek-fullstack.git`
3. Change direktory menggunakan perintah `cd energeek-fullstack`
4. Masukkan perintah `composer install` untuk menginstall data vendor
5. Masukkan perintah `cp .env.example .env` untuk menyalin file `.env`
6. Masukkan perintah `php artisan key:generate` untuk mengenerate APP_KEY
7. Masukkan perintah `php artisan migrate` untuk melakukan migrasi database
8. Jika terdapat inputan di terminal tulis `yes` kemudian enter
9. Masukkan perintah `php artisan db:seed` agar DatabaseSeeder dijalankan dan membuat data di database
10. Masukkan perintah `npm install` untuk menginstall data node_modules
11. Masukkan perintah `npm run build` or `npm run dev` agar file vuejs dapat dibuka nantinya
12. Masukkan perintah `php artisan ser` untuk menjalankan server local pada project laravel 
13. Buka terminal baru dan masukkan perintah `php artisan test` untuk menjalankan test yang ada di folder tests
