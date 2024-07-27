FROM php:8.3-fpm-alpine

WORKDIR /var/www/laravel

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --update linux-headers
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

ADD configs/xdebug.ini "${PHP_INI_DIR}/conf.d"