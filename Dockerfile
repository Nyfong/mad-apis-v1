FROM php:8.2-apache

# Install PostgreSQL PDO driver
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Enable Apache rewrite mod if you're using routes
RUN a2enmod rewrite

# Copy your project files
COPY . /var/www/html
