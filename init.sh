#!/bin/bash
set -e

echo "================================"
echo "  Vier op een Rij - Setup"
echo "================================"
echo ""

echo "📦 Installing Composer dependencies..."
composer install

echo ""
echo "🗄️  Running database migrations..."
php artisan migrate

echo ""
echo "📦 Installing NPM dependencies..."
npm install

echo ""
echo "🔨 Building frontend assets..."
npm run build

echo ""
echo "✅ Setup complete!"
echo ""
echo "================================"
echo "  Starting Laravel server..."
echo "================================"
echo ""
echo "🌐 Access the application at:"
echo "   http://localhost:8000"
echo ""

php artisan serve --host=0.0.0.0
