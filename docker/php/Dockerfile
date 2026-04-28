FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg-dev libonig-dev libxml2-dev \
    libzip-dev zlib1g-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the Xdebug source file into the container
# due to docker-compose.yml php is using context: ../, need to use full path here
COPY ./docker/php/xdebug-3.3.0.tgz /tmp/

# Manually install Xdebug
RUN tar -xvzf /tmp/xdebug-3.3.0.tgz -C /tmp/ && \
    cd /tmp/xdebug-3.3.0 && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    docker-php-ext-enable xdebug && \
    rm -rf /tmp/xdebug-3.3.0 /tmp/xdebug-3.3.0.tgz

RUN echo "xdebug.mode=coverage,debug,develop" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


COPY ./vendor /var/www/html/vendor

WORKDIR /var/www/html
