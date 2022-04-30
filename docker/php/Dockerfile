FROM php:8-fpm

WORKDIR /var/www/

RUN apt update && apt install -y \
    build-essential \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    vim \
    unzip \
    git \
    curl

# Clear cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo_pgsql 
RUN docker-php-ext-install sockets 

# Add user for laravel
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy application folder
COPY . .

# Copy existing permissions from folder to docker
COPY --chown=www:www . .
RUN chown -R www-data:www-data .

# Change current user to www
USER www

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

EXPOSE 9000
CMD ["php-fpm"]