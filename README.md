 ## How to run project on local machine
 - [x] Clone this repository
 - [x] cd /path-to-your-project
 - [x] composer install
 - [x] cp .env.example .env
 - [x] Create Database name = "backend_test" on your local mysql
 - [x] php artisan key:generate
 - [x] php artisan migrate:refresh --seed
 - [x] php artisan test

# Running Task Schedule Clear Cache at Midnight
 - php artisan schedule:run >> /dev/null 2>&1
