# Use official PHP with Apache
FROM php:8.2-apache

# Install required PHP extensions for Laravel + GD dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Enable Apache mod_rewrite (needed for Laravel routes)
RUN a2enmod rewrite

# SET Apache DocumentRoot to /var/www/html/public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/apache2.conf

# Set Working dir
WORKDIR /var/www/html

# COPY APP code
COPY . /var/www/html/

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer 

# Copy .env.example to .env for build process (will be overridden by Render env vars at runtime)
RUN cp .env.example .env || true

# Set a temporary APP_KEY just for the build process
RUN sed -i 's/APP_KEY=$/APP_KEY=base64:temporary_build_key_12345678901234567890123456789012/' .env

# Install Laravel dependencies with --no-scripts to avoid env issues
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Now run the post-install scripts
RUN composer run-script post-autoload-dump

# Install Node + npm
RUN apt-get update && apt-get install -y nodejs npm

# Build frontend assets
RUN npm install && npm run build

# Create storage directories and set permissions
RUN mkdir -p /var/www/html/public/storage/product \
    && mkdir -p /var/www/html/public/storage/picture \
    && mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/public \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Create storage link
RUN php artisan storage:link

# Expose Render's required port
EXPOSE 10000

# Start Apache
CMD ["apache2-foreground"]

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Clear and cache Laravel config (do this at runtime, not build)
# RUN php artisan config:cache