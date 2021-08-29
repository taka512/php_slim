FROM php:8.0-fpm-alpine

RUN apk upgrade --update \
    && apk add --no-cache \
        alpine-sdk \
        chromium \
        chromium-chromedriver \
        curl-dev \
        icu-dev  \
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
        intl \
        pcntl \
        pdo \
        pdo_mysql \
        zip \
    && cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime \
    && apk del tzdata \
    && rm -rf /var/cache/apk/*

WORKDIR /home/php_slim

RUN apk add --no-cache msmtp
RUN rm -rf /usr/sbin/sendmail && ln -sf /usr/bin/msmtp /usr/sbin/sendmail
ADD docker/mail/msmtprc /etc/msmtprc

ENV PANTHER_NO_SANDBOX 1
ENV PANTHER_CHROME_DRIVER_BINARY /usr/lib/chromium/chromedriver
