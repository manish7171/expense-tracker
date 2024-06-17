FROM php:8.2-fpm

ARG USER
ARG USER_ID
ARG GROUP_ID

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    vim \
    unzip \
    libicu-dev

RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql intl

RUN pecl install xdebug \
   && pecl install redis \
   && docker-php-ext-enable xdebug \
   && docker-php-ext-enable redis

COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"

RUN groupadd --force -g $GROUP_ID $USER
RUN useradd -ms /bin/bash --no-user-group -g $GROUP_ID -u 1337 $USER
RUN usermod -u $USER_ID $USER

WORKDIR /var/www
