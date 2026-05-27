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

# PHP production config (increase max_execution_time for API calls)
COPY docker/php.ini /usr/local/etc/php/conf.d/99-pokehub.ini

# Run post-install composer scripts
RUN composer dump-autoload --optimize

# Build frontend assets
RUN npm run build

# Create SQLite database (fallback if no volume mounted)
RUN mkdir -p database && touch database/database.sqlite

# Create persistent data directory for Railway volume
RUN mkdir -p /data

# Expose port
EXPOSE 3002

# Start: use persistent volume if available, then migrate + serve
CMD ["sh", "-c", "\
  if [ -d /data ]; then \
    if [ ! -f /data/database.sqlite ]; then cp database/database.sqlite /data/database.sqlite; fi; \
    export DB_DATABASE=/data/database.sqlite; \
  fi && \
  php artisan migrate --force && \
  php artisan serve --host=0.0.0.0 --port=${PORT:-3002}"]
