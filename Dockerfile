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

# Create SQLite database
RUN mkdir -p database && touch database/database.sqlite

# Expose port
EXPOSE 3002

# Start the application
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-3002}"]
