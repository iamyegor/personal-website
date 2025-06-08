FROM php:8.2-apache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install required packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Copy Apache configurations
COPY enable-htaccess.conf /etc/apache2/conf-available/enable-htaccess.conf

# Enable configurations
RUN a2enconf enable-htaccess

WORKDIR /var/www/html

# Copy composer files first for better Docker layer caching
COPY composer.json composer.lock* ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# This will copy your website files AND the .htaccess file from your project root
COPY . /var/www/html/

EXPOSE 80 