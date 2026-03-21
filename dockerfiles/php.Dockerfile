FROM php:8.3-fpm-alpine

WORKDIR /var/www/laravel

# Установка зависимостей
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    linux-headers \
    curl-dev \
    openssl-dev \
    libssl3 \
    mongo-c-driver-dev \
    cyrus-sasl-dev

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Добавление конфигурации Xdebug
COPY configs/xdebug.ini "${PHP_INI_DIR}/conf.d"

# Очистка кэша
RUN rm -rf /tmp/pear \
    && docker-php-source delete
