## 🛠️ Instalasi Cepat (via Terminal)

```bash
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

# Buat database SQLite
touch database/database.sqlite

# Generate app key Laravel
php artisan key:generate

# Migrasi dan seed database
php artisan migrate --seed

# Jalankan server lokal Laravel
php artisan serve

