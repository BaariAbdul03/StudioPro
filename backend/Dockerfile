FROM dunglas/frankenphp:1-php8.3-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    postgresql-client \
    git \
    unzip \
    && install-php-extensions \
    pcntl \
    pdo_pgsql \
    pgsql \
    opcache \
    zip \
    bcmath

# Set working directory
WORKDIR /app

# Copy dependency files first for caching
COPY composer.json composer.lock ./

# Install vendor dependencies without dev utilities
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist --optimize-autoloader

# Copy application source code
COPY . .

# Run post-install scripts and optimize autoloading
RUN composer dump-autoload --no-dev --optimize

# Set permissions for Laravel directory structure
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Configure FrankenPHP document root and port
ENV FRANKENPHP_DOCUMENT_ROOT=/app/public
ENV PORT=8000

EXPOSE 8000

# Start FrankenPHP server
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
