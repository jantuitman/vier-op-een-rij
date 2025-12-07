#!/bin/sh
set -e

echo "Starting Vier op een Rij..."

# Ensure .env exists
if [ ! -f /var/www/html/.env ]; then
    echo "Warning: .env file not found, creating empty one"
    touch /var/www/html/.env
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Cache configuration for performance
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application ready!"

# Execute the main command
exec "$@"
