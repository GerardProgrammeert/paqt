#!/bin/bash

echo "Start clean database"
php artisan migrate:refresh
echo "Ended clean database"

echo "Start migration"
php artisan migrate
echo "Ended migration"

echo "Start seeding database"
php artisan db:seed --class DecisionSeeder
php artisan db:seed --class RideSeeder
php artisan db:seed --class AccountSeeder
echo "Ended seeding database"

php artisan migrate --env=testing
