FROM php:8.1

RUN apt-get update -y && apt-get upgrade -y

RUN pecl install openswoole-4.9.1 && docker-php-ext-enable openswoole
RUN pecl install redis && docker-php-ext-enable redis

RUN apt-get install -y zip git

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && chmod +x composer.phar \
    && mv composer.phar /usr/bin/composer

RUN mkdir -p /var/php && chmod 777 /var/php

RUN useradd --create-home test
USER test

WORKDIR /var/php

RUN mkdir /var/php/vendor && mkdir -p /var/php/test/vendor && mkdir -p /var/php/test/tests

COPY composer.json composer.json

RUN composer update && composer install
