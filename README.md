# Clone repositori
git clone https://github.com/prabhasetabalatara/kasirlaravel.git

# Masuk ke direktori proyek
cd kasirlaravel

# Install dependensi PHP
composer install

# Install dependensi JavaScript
npm install

# Salin file konfigurasi lingkungan
cp .env.example .env

# Buat file database SQLite
cd database
#
touch database/database.sqlite
cd ..

# Edit file .env dan pastikan konfigurasi database seperti berikut:
DB_CONNECTION=sqlite
#
DB_DATABASE=/full/path/to/database/database.sqlite

# Contoh (gantilah dengan path project kamu):
DB_DATABASE=database/database.sqlite

# Untuk Windows (gunakan double backslash atau slash biasa):
DB_DATABASE=C:/laragon/www/kasirlaravel/database/database.sqlite

# Generate app key Laravel
php artisan key:generate

# Jalankan migrasi dan seed database
php artisan migrate --seed

# Jalankan server lokal Laravel
php artisan serve


