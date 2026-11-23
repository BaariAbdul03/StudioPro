#!/bin/sh
set -eu

case "${PORT:-80}" in
    ''|*[!0-9]*) echo "PORT must be numeric" >&2; exit 1 ;;
esac

export PORT="${PORT:-80}"
printf 'Listen %s\n' "$PORT" > /etc/apache2/ports.conf

if [ "${APP_ENV:-production}" = production ]; then
    : "${APP_KEY:?Configure APP_KEY}"
    : "${APP_URL:?Configure APP_URL}"
    : "${DB_URL:-${DATABASE_URL:?Configure the pooled Neon database URL}}"
    : "${NEON_STORAGE_ENDPOINT:?Configure the Neon storage endpoint}"
    : "${NEON_STORAGE_ACCESS_KEY_ID:?Configure Neon storage access}"
    : "${NEON_STORAGE_SECRET_ACCESS_KEY:?Configure Neon storage access}"
    case "$NEON_STORAGE_ENDPOINT" in
        https://*) ;;
        *) echo "NEON_STORAGE_ENDPOINT must use HTTPS" >&2; exit 1 ;;
    esac
fi

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views
chown -R www-data:www-data storage/framework

exec apache2-foreground
