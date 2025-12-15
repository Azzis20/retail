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
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Enable Apache mod_rewrite (needed for Laravel routes)
RUN a2enmod rewrite

# Configure Apache to listen on PORT environment variable (Render requirement)
RUN sed -i 's/Listen 80/Listen ${PORT:-10000}/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT:-10000}>/' /etc/apache2/sites-available/000-default.conf

# SET Apache DocumentRoot to /var/www/html/public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set Working dir
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer 

# COPY APP code
COPY . /var/www/html/

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

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

# Expose Render's required port
EXPOSE 10000

# Start Apache
CMD ["apache2-foreground"]