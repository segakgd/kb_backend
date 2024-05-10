# Use PHP 8.2 as the base image
FROM php:8.2

# Update package lists and install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    autoconf \
    pkg-config \
    libssl-dev \
    libpq-dev \
    libmemcached-dev \
    zlib1g-dev \
    git \
    unzip \
    nano \
    librabbitmq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install additional PHP extension dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-configure intl && docker-php-ext-install intl

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Install AMQP extension
RUN pecl install amqp && docker-php-ext-enable amqp

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
RUN apt-get update && \
    apt-get install -y nodejs npm && \
    npm install -g n && \
    n stable

# Set the working directory inside the container
WORKDIR /app

# Copy the application code to the container
COPY ../ /app

# Expose port 9000 for potential connections
EXPOSE 9000

# Start a long-running process to keep the container running
CMD ["tail", "-f", "/dev/null"]



