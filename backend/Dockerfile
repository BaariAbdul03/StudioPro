# --- Stage 1: Build Vue/Vite Frontend SPA ---
FROM node:20-alpine AS frontend-builder
WORKDIR /frontend

# Copy package configurations and install dependencies
COPY frontend/package*.json ./
RUN npm install

# Copy frontend source code and compile production assets
COPY frontend/ ./
ENV VITE_API_BASE_URL=/api
RUN npm run build

# --- Stage 2: Build PHP Apache Backend Server ---
FROM php:8.3-apache AS runner

# Copy official composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install system dependencies, Node.js, npm, and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    postgresql-client \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    git \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install \
    pcntl \
    pdo_pgsql \
    pgsql \
    opcache \
    zip \
    bcmath \
    && rm -rf /var/lib/apt/lists/* \
    && ln -sf /usr/bin/nodejs /usr/bin/node

# Set working directory
WORKDIR /var/www/html

# Copy Composer files and install production dependencies
COPY backend/composer.json backend/composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist --optimize-autoloader

# Copy backend package configurations and install Node dependencies for Stitch bridge
COPY backend/package*.json ./
RUN npm install --omit=dev

# Copy backend application files
COPY backend/ .

# Copy built frontend static assets into Laravel's public directory
COPY --from=frontend-builder /frontend/dist/ ./public/

# Run post-install scripts and optimize autoloading
RUN composer dump-autoload --no-dev --optimize

# Change Apache document root to Laravel's public directory
RUN sed -i -e "s|/var/www/html|/var/www/html/public|g" /etc/apache2/sites-available/000-default.conf

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Set permissions for storage directories
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]
