FROM php:8.2-apache


# Install PostgreSQL PDO driver
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Copy all project files into the container
COPY . /var/www/html

# Give permissions inside the container
RUN mkdir -p /var/www/html && \
    touch /var/www/html/debug.log && \
    chmod -R 777 /var/www/html

# Enable Apache rewrite mod if you're using routes
RUN a2enmod rewrite

# Copy your project files
COPY . /var/www/html
