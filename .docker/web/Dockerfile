FROM composer:lts as deps
WORKDIR /app
RUN --mount=type=bind,source=../../src/composer.json,target=composer.json \
    --mount=type=bind,source=../../src/composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction

FROM php:8.3-apache

COPY ../../ /var/www/html

COPY --from=deps app/vendor/ /var/www/html/src/vendor

COPY .docker/web/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN \
    apt update && apt upgrade -y && apt install nano -y \
    && apt install inetutils-ping -y && apt install git -y \
    && docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql \
    && a2enmod rewrite && service apache2 restart

WORKDIR /var/www/html/src

USER www-data

CMD ["apache2-foreground"]
