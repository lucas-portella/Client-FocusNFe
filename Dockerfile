FROM php:7.4-apache

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    && docker-php-ext-install \
        zip \
        pdo \
        pdo_mysql \
        curl \
        mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

