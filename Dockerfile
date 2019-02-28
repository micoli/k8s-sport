FROM php:7.3.2-cli-alpine3.8
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;\
    mkdir -p /var/log; \
    mkdir /application; \
    apk add make

CMD touch /var/log/test.log && tail -f /var/log/test.log

ARG APP_ENV
ENV APP_ENV=$APP_ENV

WORKDIR /application

COPY composer.* ./
COPY symfony.lock ./
RUN composer install --no-dev --no-scripts

COPY bin bin
COPY config config
COPY public public
COPY src src
COPY tests tests


COPY Makefile ./
COPY phpunit.xml.dist ./
COPY .php_cs.dist ./
COPY .env ./

RUN composer dump-autoload
