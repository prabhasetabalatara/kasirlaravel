🛠️ Instalasi Cepat (terminal)
git clone https://github.com/prabhasetabalatara/kasirlaravel.git
cd kasirlaravel
composer install
npm install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
php artisan migrate --seed
php artisan serve
