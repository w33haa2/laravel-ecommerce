#!/bin/bash

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -z "$APP_KEY" ]; then
    # Check if .env already has a valid key
    if ! grep -q "^APP_KEY=base64:" .env; then
        # If APP_KEY is empty or unset, we want to generate one.
        # But if it's empty (but set), we must unset it so Laravel reads the generated one from .env
        unset APP_KEY
        php artisan key:generate
    fi
fi

# Force APP_DEBUG to true to debug the issue
export APP_DEBUG=true

php artisan config:clear
php artisan cache:clear
php artisan migrate --force
php artisan db:seed --class=ProductSeeder --force

php artisan serve --host=0.0.0.0 --port=80
