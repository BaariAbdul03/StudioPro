FROM node:24.18.0-bookworm-slim AS node

FROM node AS frontend
WORKDIR /frontend
COPY frontend/package.json frontend/package-lock.json ./
RUN npm ci --ignore-scripts
COPY frontend/ ./
RUN npm run build

FROM node AS stitch
WORKDIR /stitch
COPY backend/package.json backend/package-lock.json ./
RUN npm ci --omit=dev --ignore-scripts

FROM php:8.4.28-apache
COPY --from=composer:2.10.2 /usr/bin/composer /usr/bin/composer
COPY --from=node /usr/local/bin/node /usr/local/bin/node

RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates libatomic1 libpq-dev libzip-dev unzip \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql opcache zip pcntl \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY backend/composer.json backend/composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist --optimize-autoloader

COPY backend/app ./app
COPY backend/artisan ./artisan
COPY backend/bootstrap ./bootstrap
COPY backend/config ./config
COPY backend/database ./database
COPY backend/public ./public
COPY backend/resources ./resources
COPY backend/routes ./routes
COPY backend/scripts/stitch-bridge.js ./scripts/stitch-bridge.js
COPY backend/package.json ./package.json
COPY --from=stitch /stitch/node_modules ./node_modules
COPY backend/storage ./storage
COPY --from=frontend /frontend/dist/ ./public/
COPY frontend/src/data/demo.html frontend/src/data/demo.css ./resources/demo/

RUN composer dump-autoload --no-dev --optimize \
    && a2enmod rewrite headers \
    && chown -R www-data:www-data storage bootstrap/cache

COPY backend/docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY backend/docker/php.ini /usr/local/etc/php/conf.d/studio.ini
COPY backend/docker/start.sh /usr/local/bin/studio-start
COPY backend/docker/migrate.sh /usr/local/bin/studio-migrate

ENV PORT=80
ENV DB_SSLROOTCERT=/etc/ssl/certs/ca-certificates.crt
EXPOSE 80
CMD ["sh", "/usr/local/bin/studio-start"]
