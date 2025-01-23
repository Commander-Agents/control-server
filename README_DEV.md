# Dev
## Build models :

`php artisan code:models` --> https://github.com/reliese/laravel

## Telescope

In local mode only, go to `http://localhost:8000/telescope`

## Optimisation

- OPCache : Build php files in bytecode : https://medium.com/appstract/make-your-laravel-app-fly-with-php-opcache-9948db2a5f93
- Jobs : Use `queue:work`
- Artisan cache : Use `php artisan optimize:clear && php artisan optimize` OR
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

# TODO

- MQTT TLS