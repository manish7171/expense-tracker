FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
  git \
  curl \
  zip \
  unzip

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www
