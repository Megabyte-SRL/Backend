FROM php:7.4-apache

# Install system dependencies
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
    libfreetype6-dev \
    git

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    gd \
    intl \
    bz2 \
    bcmath \
    sockets

# 2. apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application source from the 'src' directory
COPY src/ /var/www/html

# Verify contents (debugging step to ensure files are copied correctly)
RUN ls -al /var/www/html

# Copy Composer binary from the Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Allow Composer to run as super user
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install project dependencies with verbose output
RUN composer install --no-interaction -vvv

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
