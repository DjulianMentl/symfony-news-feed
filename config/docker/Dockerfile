FROM php:8.1.0-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    g++ \
    git \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    iproute2

RUN pecl install xdebug apcu \
    && docker-php-ext-enable xdebug apcu \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql intl opcache

RUN apt-get update \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Установка Symfony CLI
#RUN curl -sS https://get.symfony.com/cli/installer | bash \
#    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www/symfony
COPY --chown=www:www . /var/www/symfony

WORKDIR /var/www/symfony

USER www
