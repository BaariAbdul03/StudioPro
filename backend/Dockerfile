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

# --- Stage 2: Build FrankenPHP Backend Server ---
FROM dunglas/frankenphp:1-php8.3-alpine AS runner

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

# Copy Composer files and install production dependencies
COPY backend/composer.json backend/composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist --optimize-autoloader

# Copy backend application files
COPY backend/ .

# Copy built frontend static assets into Laravel's public directory
COPY --from=frontend-builder /frontend/dist/ ./public/

# Run post-install scripts and optimize autoloading
RUN composer dump-autoload --no-dev --optimize

# Set permissions for storage directories
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Configure FrankenPHP document root and port
ENV FRANKENPHP_DOCUMENT_ROOT=/app/public
ENV PORT=8000

EXPOSE 8000

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
