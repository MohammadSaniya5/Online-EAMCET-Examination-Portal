FROM php:8.1-apache

# Install database extensions required by PDO and MySQLi
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (often useful for PHP apps)
RUN a2enmod rewrite

# Copy your application files into the Docker container
COPY . /var/www/html/

# Expose port 80 for Render or local running
EXPOSE 80
