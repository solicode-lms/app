$env:APP_ENV = "production"

php artisan migrate:fresh
php artisan db:seed

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan serve