FROM phpearth/php:7.3-nginx

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;\
    mkdir -p /var/log; \
    mkdir /application; \
    apk add make

ARG APP_ENV
ENV APP_ENV=$APP_ENV


WORKDIR /application

COPY composer.* ./
COPY symfony.lock ./
RUN composer install --no-dev --no-scripts

COPY .docker /

COPY bin bin
COPY config config
COPY public public
COPY src src
COPY templates templates
COPY tests tests

COPY Makefile ./
COPY phpunit.xml.dist ./
COPY .php_cs.dist ./
COPY .env ./

RUN composer dump-autoload; \
    mkdir var; \
     chmod 777 var; \
     bin/console cache:warmup; \
     rm -f /var/log/nginx/access.log;
     #rm -f /var/log/nginx/error.log

