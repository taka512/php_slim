FROM php:7.2-fpm-alpine

RUN apk upgrade --update \
    && apk add \
        alpine-sdk \
        chromium \
        chromium-chromedriver \
        curl-dev \
        iputils \
        libxml2-dev \
        mysql-client \
        net-tools \
        libressl-dev \
        tzdata \
        zlib-dev \
    && docker-php-ext-install \
        curl \
        dom \
        mbstring \
        pdo \
        pdo_mysql \
        zip \
    && cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
    && apk del tzdata \
    && rm -rf /var/cache/apk/*

WORKDIR /home/php_slim
ENV PANTHER_CHROME_DRIVER_BINARY /usr/lib/chromium/chromedriver
