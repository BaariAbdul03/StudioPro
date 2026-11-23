#!/bin/sh
set -eu

case "${PORT:-80}" in
    ''|*[!0-9]*) echo "PORT must be numeric" >&2; exit 1 ;;
esac

export PORT="${PORT:-80}"

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

export APACHE_RUN_DIR=/tmp/studio/apache
export APACHE_LOCK_DIR="$APACHE_RUN_DIR"
export APACHE_PID_FILE="$APACHE_RUN_DIR/apache2.pid"
export LARAVEL_STORAGE_PATH=/tmp/studio/storage

mkdir -p "$APACHE_RUN_DIR" "$LARAVEL_STORAGE_PATH/framework/cache/data" \
    "$LARAVEL_STORAGE_PATH/framework/sessions" "$LARAVEL_STORAGE_PATH/framework/views" \
    "$LARAVEL_STORAGE_PATH/logs"
chown -R www-data:www-data "$LARAVEL_STORAGE_PATH"

exec apache2-foreground
