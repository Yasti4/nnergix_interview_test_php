FROM php:7.3-apache-stretch

ARG COMPOSER_VERSION=1.8.6

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION};

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y git zip unzip supervisor

RUN docker-php-ext-install pdo pdo_mysql;

RUN rm -r /var/lib/apt/lists/*

RUN mkdir -p /app
RUN mkdir -p /app/var
RUN mkdir -p /app/var/log/php-cli

RUN echo '' >> /app/.env

COPY composer.lock composer.json /app/
COPY bin /app/bin
COPY config /app/config
COPY public /app/public
COPY src /app/src

COPY docker/local/nnergix-php/rootfs /

COPY docker/local/nnergix-php/rootfs/supervisor /etc/supervisor

RUN adduser www-data www-data
RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /app/var

WORKDIR /app

# RUN composer install

ENV APACHE_NO_ACCESS_LOG=true COMPOSER_ALLOW_SUPERUSER=1

CMD ["supervisord","-n", "-c","/etc/supervisor/supervisord.conf"]
