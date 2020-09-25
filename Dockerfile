FROM php:7.4-fpm-alpine

RUN apk upgrade --update \
    && apk add --no-cache \
        alpine-sdk \
        chromium \
        chromium-chromedriver \
        curl-dev \
        iputils \
        libxml2-dev \
        mysql-client \
        net-tools \
        libressl-dev \
        libzip-dev \
        oniguruma-dev \
        tzdata \
    && docker-php-ext-install \
        curl \
        dom \
        pdo \
        pdo_mysql \
        zip \
    && cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
    && apk del tzdata \
    && rm -rf /var/cache/apk/*

WORKDIR /home/php_slim

ENV PANTHER_NO_SANDBOX 1
ENV PANTHER_CHROME_DRIVER_BINARY /usr/lib/chromium/chromedriver
