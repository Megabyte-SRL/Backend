FROM php:7.4-apache

# 1. Install dependencies
RUN apt-get update && apt-get install -y \
    zip \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev

# Install additional PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    gd \
    intl \
    bz2

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application source
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Copy Composer binary from the Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install project dependencies with correct Composer command
RUN composer install --no-interaction

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80