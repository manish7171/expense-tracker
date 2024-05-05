FROM php:8.2-fpm

ARG USER
ARG USER_ID
ARG GROUP_ID

RUN apt-get update && apt-get install -y \
  git \
  curl \
  zip \
  unzip

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug \ 
   && docker-php-ext-enable xdebug

COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"

RUN groupadd --force -g $GROUP_ID $USER
RUN useradd -ms /bin/bash --no-user-group -g $GROUP_ID -u 1337 $USER
RUN usermod -u $USER_ID $USER

WORKDIR /var/www
