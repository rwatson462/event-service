FROM php:8.1

RUN mkdir -p /var/www/html
RUN mkdir -p /var/www/vendor

RUN pecl install openswoole && docker-php-ext-enable openswoole
RUN pecl install redis && docker-php-ext-enable redis
