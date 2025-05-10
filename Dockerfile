FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Copy custom Apache configuration to enable .htaccess and set AllowOverride
COPY enable-htaccess.conf /etc/apache2/conf-available/enable-htaccess.conf
RUN a2enconf enable-htaccess

WORKDIR /var/www/html

# This will copy your website files AND the .htaccess file from your project root
COPY . /var/www/html/

EXPOSE 80 