FROM php:8.3-apache

# Instala dependências e certificados SSL
RUN apt-get update && apt-get install -y \
    ca-certificates \
    libzip-dev \
    unzip \
    git \
    && update-ca-certificates \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Define a pasta public como DocumentRoot
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Habilita o mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html