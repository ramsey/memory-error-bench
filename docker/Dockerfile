FROM debian:13

RUN apt-get update \
    && apt-get install -y \
        autoconf \
        bison \
        ca-certificates \
        freetds-dev \
        g++ \
        gcc \
        git \
        libargon2-dev \
        libavif-dev \
        libbz2-dev \
        libcurl4-openssl-dev \
        libenchant-2-dev \
        libfreetype-dev \
        libicu-dev \
        libjpeg-dev \
        libonig-dev \
        libpng-dev \
        libpq-dev \
        libreadline-dev \
        libsasl2-dev \
        libsnmp-dev \
        libsodium-dev \
        libsqlite3-dev \
        libtidy-dev \
        libwebp-dev \
        libxml2-dev \
        libxpm-dev \
        libxslt1-dev \
        libzip-dev \
        make \
        openssl \
        php-cli \
        pkg-config \
        re2c \
        unixodbc-dev \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/lib/snmp/cert_indexes

COPY config.release /usr/src/config.release

ARG PHP_SRC_REPO=https://github.com/php/php-src.git
ARG PHP_SRC_REF=master

RUN set -eux \
    && mkdir -p /usr/src/php-src \
    && cd /usr/src/php-src \
    && git init \
    && git fetch --depth 1 "$PHP_SRC_REPO" "$PHP_SRC_REF" \
    && git checkout --detach FETCH_HEAD \
    && ./buildconf \
    && sh /usr/src/config.release \
    && make -j"$(nproc)" \
    && apt-get remove -y php-cli \
    && make install \
    && php -v \
    && cd / \
    && rm -rf /usr/src/php-src

WORKDIR /workspace
