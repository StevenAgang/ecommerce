FROM php:7.4.33-apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . . 

RUN a2enmod rewrite

RUN useradd -m composeruser

USER composeruser

COPY --chown=composeruser:composeruser . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install

EXPOSE 80