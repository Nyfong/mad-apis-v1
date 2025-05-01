FROM php:8.2-apache

# Install PostgreSQL PDO driver
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Set permissions (this is the line you asked about)
RUN chmod -R 777 /var/www/html

# Enable Apache rewrite mod if you're using routes
RUN a2enmod rewrite

# Copy your project files
COPY . /var/www/html
