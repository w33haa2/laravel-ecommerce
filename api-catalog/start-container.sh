#!/bin/bash

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -z "$APP_KEY" ]; then
    # If APP_KEY is empty or unset, we want to generate one.
    # But if it's empty (but set), we must unset it so Laravel reads the generated one from .env
    unset APP_KEY
    php artisan key:generate
fi
php artisan migrate --force
php artisan db:seed --class=ProductSeeder --force

php artisan serve --host=0.0.0.0 --port=80
