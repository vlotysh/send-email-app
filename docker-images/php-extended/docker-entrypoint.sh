#!/bin/sh

sleep 5

FILE=.env
if [ -f "$FILE" ]; then
    echo "$FILE exists."
else
    cp .env.example .env
    echo "$FILE does not exist."
fi

composer install

# Run Laravel migrations
php artisan migrate --force  --no-interaction

# Start the PHP-FPM process
exec php-fpm
