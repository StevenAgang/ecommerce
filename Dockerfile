FROM php:7.4.33-apache

RUN apt-get update && apt-get install -y curl git

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . . 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN useradd -m composeruser

USER composeruser

RUN composer install

RUN a2enmod rewrite

EXPOSE 80