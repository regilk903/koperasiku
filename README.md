# KoperasiKu (Laravel 12 + Breeze + Bootstrap 5 + MySQL)

## Pasang
Prasyarat: PHP 8.2+, Composer, Node.js/npm, MySQL (database `koperasiku` sudah dibuat).

    chmod +x setup.sh && ./setup.sh
    cd koperasiku
    # sesuaikan DB_USERNAME / DB_PASSWORD di .env
    php artisan migrate --seed
    php artisan serve        # http://127.0.0.1:8000

Akun contoh (lokal saja, password `password123`): admin@, pengurus@, bendahara@koperasiku.test

## Hak akses (dicek di server lewat middleware `role`)
- admin: semua + CRUD user
- pengurus: kelola anggota
- bendahara: catat & verifikasi simpanan
- anggota: hanya melihat data miliknya (hubungkan akun lewat form anggota)
