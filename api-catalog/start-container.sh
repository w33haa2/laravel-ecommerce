#!/bin/bash

if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan key:generate
php artisan migrate --force
php artisan db:seed --class=ProductSeeder --force

php artisan serve --host=0.0.0.0 --port=80
