#!/bin/sh
set -eu

: "${DB_DIRECT_URL:-${DATABASE_URL_UNPOOLED:?Configure the direct Neon migration URL}}"
exec php artisan migrate --database=pgsql_direct --force
