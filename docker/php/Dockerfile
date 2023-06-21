FROM php:7.4.3-fpm

RUN docker-php-ext-install mysqli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl channel-update pecl.php.net

RUN pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug

COPY conf.d/* $PHP_INI_DIR/conf.d