#!/bin/bash
set -e

# Change directory to project root
cd /var/www/html

# Run composer install if composer.json exists
if [ -f "composer.json" ] && [ ! -f "vendor/autoload.php" ]; then
    echo "Installing dependencies..."
    composer install --no-interaction --prefer-dist
fi

# Ensure writable directory permissions
echo "🛡 Setting permissions for writable/ folder..."
chmod -R 777 writable/

# Execute CMD
exec "$@"
