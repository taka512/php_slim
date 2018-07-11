FROM php:7.2-fpm-alpine

RUN apk upgrade --update \
    && apk add \
        alpine-sdk \
        curl-dev \
        iputils \
        libxml2-dev \
        mysql-client \
        net-tools \
        openssl-dev \
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

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_NO_INTERACTION 1
