#!/usr/bin/env bash
# Jalankan dari folder yang berisi folder "overlay". Butuh PHP 8.2+, Composer, Node.js, MySQL.
set -e
composer create-project laravel/laravel koperasiku "^12.0"
cd koperasiku
composer require laravel/breeze --dev
php artisan breeze:install blade --no-interaction
cp -r ../overlay/. .
# Bootstrap dipisah dari Tailwind milik Breeze agar halaman login tidak rusak
sed -i "s#'resources/js/app.js'#'resources/js/app.js', 'resources/css/koperasi.css', 'resources/js/koperasi.js'#" vite.config.js
npm install
npm install bootstrap @popperjs/core
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/; s/^# *DB_HOST=.*/DB_HOST=127.0.0.1/; s/^# *DB_PORT=.*/DB_PORT=3306/; s/^# *DB_DATABASE=.*/DB_DATABASE=koperasiku/; s/^# *DB_USERNAME=.*/DB_USERNAME=root/; s/^# *DB_PASSWORD=.*/DB_PASSWORD=/' .env
npm run build
echo "Buat database MySQL 'koperasiku', sesuaikan .env, lalu: php artisan migrate --seed && php artisan serve"
