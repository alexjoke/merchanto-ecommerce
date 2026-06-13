#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
    echo "→ Installing Composer dependencies..."
    composer install --prefer-dist --no-interaction
fi

if [ ! -f config/modules.php ]; then
    echo "→ Publishing laravel-modules config..."
    php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider" --tag=config --force
fi

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    php artisan key:generate --force
fi

echo "→ Waiting for PostgreSQL..."
until php -r "
try {
    new PDO(
        'pgsql:host=${DB_HOST:-postgres};port=${DB_PORT:-5432};dbname=${DB_DATABASE:-merchanto}',
        '${DB_USERNAME:-merchanto}',
        '${DB_PASSWORD:-secret}'
    );
    exit(0);
} catch (Exception \$e) {
    exit(1);
}
" 2>/dev/null; do
    sleep 2
done

php artisan migrate --force
php artisan db:seed --force

exec "$@"
