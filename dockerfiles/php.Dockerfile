FROM php:8.3-fpm-alpine

WORKDIR /var/www/laravel

# Установка зависимостей
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    linux-headers \
    curl-dev \
    openssl-dev \
    libssl3 \
    && apk add --no-cache --virtual .build-deps \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apk del .build-deps

# Установка и настройка расширений PHP
RUN docker-php-ext-install pdo pdo_mysql

# Установка Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Добавление конфигурации MongoDB в php.ini
RUN echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini

# Добавление конфигурации Xdebug
COPY configs/xdebug.ini "${PHP_INI_DIR}/conf.d"

# Очистка кэша
RUN rm -rf /tmp/pear \
    && docker-php-source delete