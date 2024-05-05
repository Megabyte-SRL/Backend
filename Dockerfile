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

RUN docker-php-ext-install \
    pdo \
    pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application source
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install project dependencies
RUN componser install --no-interation

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80