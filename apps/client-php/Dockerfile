FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli

# Enable mod_rewrite and proxy modules
RUN a2enmod rewrite proxy proxy_http

# Overwrite the default Apache config with the one inside our app
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files to the container
COPY . /var/www/html/

# Set permissions for the web server
RUN chown -R www-data:www-data /var/www/html/

# Expose port 80
EXPOSE 80