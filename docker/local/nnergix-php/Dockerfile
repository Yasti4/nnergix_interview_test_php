FROM php:7.3-apache-stretch

ARG HOST_IP
ARG COMPOSER_VERSION=1.7.2
ARG XDEBUG_VERSION=2.7.2

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION};

RUN apt-get update \
    && apt-get install -y git zip unzip libzip-dev

RUN docker-php-ext-configure zip --with-libzip=/usr/include
RUN docker-php-ext-install zip pdo pdo_mysql

RUN a2enmod rewrite

RUN pecl install xdebug-${XDEBUG_VERSION}; \
    docker-php-ext-enable xdebug;

RUN mkdir -p /app
COPY docker/local/nnergix-php/rootfs/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_host=$HOST_IP" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN export PHP_IDE_CONFIG="serverName=docker"

ENV APACHE_NO_ACCESS_LOG=true COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

CMD ["apache2-foreground"]
