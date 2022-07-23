FROM php:8.1

RUN mkdir -p /var/www

RUN pecl install openswoole-4.9.1 && docker-php-ext-enable openswoole
RUN pecl install redis && docker-php-ext-enable redis
