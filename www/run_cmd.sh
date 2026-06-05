#!/bin/sh
php /var/www/html/artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --tag=config --force
