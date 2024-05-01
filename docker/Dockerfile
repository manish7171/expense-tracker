FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
  git \
  curl \
  zip \
  unzip

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug \ 
   && docker-php-ext-enable xdebug

COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"

WORKDIR /var/www
