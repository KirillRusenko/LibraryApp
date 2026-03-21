FROM composer:latest

RUN apk add --no-cache \
    $PHPIZE_DEPS \
    linux-headers \
    curl-dev \
    openssl-dev \
    libssl3 \
    mongo-c-driver-dev \
    cyrus-sasl-dev

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /var/www/laravel

ENTRYPOINT ["composer", "--ignore-platform-reqs"]