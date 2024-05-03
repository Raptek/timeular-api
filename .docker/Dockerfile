ARG PHP_VERSION=8.3
ARG ALPINE_VERSION=3.19

FROM php:${PHP_VERSION}-cli-alpine${ALPINE_VERSION}

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions xdebug-^3@stable

COPY .docker/conf.d /usr/local/etc/php/conf.d
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/conf.d/php.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/app
