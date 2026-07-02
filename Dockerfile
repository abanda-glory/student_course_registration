FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader

# Set permissions
RUN mkdir -p public/api-docs && chmod -R 777 storage bootstrap/cache public/api-docs

# Expose port
EXPOSE 10000

# Start command
CMD php artisan migrate --force && php artisan db:seed --force && php artisan config:clear && php artisan l5-swagger:generate && php artisan serve --host=0.0.0.0 --port=10000
