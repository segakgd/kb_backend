FROM devilbox/php-fpm-8.2:latest

WORKDIR /var/www/KBackend

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    zip \
    unzip \
    git \
    && apt-get clean

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the composer files
COPY ../../../composer.json ./
COPY ../../../composer.lock ./

# Install PHP dependencies
RUN composer install --no-interaction --no-plugins --no-scripts
RUN mkdir -p var && chmod -R 777 var

COPY ../../../_conf_local/php-fpm/php.ini /usr/local/etc/php/conf.d/custom.ini

EXPOSE 9000
