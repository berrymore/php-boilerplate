FROM ghcr.io/roadrunner-server/roadrunner:2025 AS roadrunner
FROM composer/composer:latest-bin AS composer
FROM php:8.4-cli-bookworm

COPY --from=roadrunner /usr/bin/rr /usr/local/bin/rr
COPY --from=composer /composer /usr/bin/composer

ENV DEBIAN_FRONTEND=noninteractive
ARG ARCH=amd64

RUN \
    apt-get -y update \
    && apt-get -y --no-install-recommends install \
        libzip-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libicu-dev \
        libxml2-dev \
        libmagickwand-dev \
        libyaml-dev \
        libcurl4-openssl-dev \
        libssl-dev \
        libpq-dev \
        git \
        wget \
        xfonts-base \
        xfonts-75dpi \
        xfonts-utils \
        libfontenc1 \
        libxfont2 \
        xfonts-encodings \
    && docker-php-source extract \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd bcmath zip intl soap exif sockets opcache pdo_pgsql pgsql \
    && pecl install -s apcu yaml grpc xdebug \
    && docker-php-ext-enable apcu yaml grpc xdebug \
    && docker-php-source delete \
    && apt-get -y purge \
        libzip-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libicu-dev \
        libxml2-dev \
        libmagickwand-dev \
        libyaml-dev \
        libcurl4-openssl-dev \
        libssl-dev \
        wget \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && rm /var/log/dpkg.log \
    && rm -rf /tmp/*

COPY --chown=root:www-data php.ini /usr/local/etc/php
COPY --chown=root:www-data docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d

ENV OPENSSL_CONF=/dev/null

USER www-data
CMD rr serve -c .rr.yaml
