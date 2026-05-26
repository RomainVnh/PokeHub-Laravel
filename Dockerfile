FROM php:8.4-cli AS base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libsqlite3-dev libxml2-dev libcurl4-openssl-dev \
    libonig-dev nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite mbstring xml \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy package files and install Node dependencies
COPY package.json package-lock.json ./
RUN npm ci

# Copy the rest of the application
COPY . .

# Run post-install composer scripts
RUN composer dump-autoload --optimize

# Build frontend assets
RUN npm run build

# Cache views only (config/routes need runtime env vars from Railway)
RUN php artisan view:cache || true

# Create SQLite database
RUN mkdir -p database && touch database/database.sqlite

# Expose port
EXPOSE ${PORT:-3002}

# Start command: clear stale config cache, cache config with runtime env vars, migrate, then serve
CMD php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-3002}
