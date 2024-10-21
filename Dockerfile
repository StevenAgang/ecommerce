FROM php:7.4.33-apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . . 

RUN a2enmod rewrite

RUN composer install

EXPOSE 80