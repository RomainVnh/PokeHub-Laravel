#!/bin/bash
cd '/Users/romain/Documents/DEV/Full Stack/PokeHub-Laravel'
npx vite --port=5174 &
php artisan serve --port=3002 &
wait
