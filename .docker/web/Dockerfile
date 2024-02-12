FROM php:8.3-apache

COPY ${PWD} /var/www/html

COPY .docker/web/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite && service apache2 restart

WORKDIR /var/www/html/src

RUN \
    apt update && apt upgrade -y && apt install nano -y && apt install inetutils-ping -y \
    && docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install

CMD ["apache2-foreground"]
