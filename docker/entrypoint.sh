#!/bin/sh
set -e

# Run artisan package discover (requires DB to be available)
php artisan package:discover --ansi || true

# Run migrations if needed
php artisan migrate --force || true

# Seed database (roles & users) if not already seeded
php artisan db:seed --force || true

# Create storage symlink
php artisan storage:link || true

# Clear and cache config for production
php artisan config:cache || true
php artisan route:cache || true

exec "$@"
