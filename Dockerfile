FROM phpearth/php:7.3-nginx

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;\
    mkdir -p /var/log; \
    mkdir /application; \
    apk add make git httpie

RUN composer global require hirak/prestissimo

ARG APP_ENV
ENV APP_ENV=$APP_ENV

WORKDIR /application

COPY composer.* ./
COPY symfony.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist


COPY phpunit.xml.dist ./
COPY .php_cs.dist ./
COPY .env ./
COPY Makefile ./

COPY .docker/ /

COPY bin bin
COPY public public
COPY tests tests
COPY templates templates
COPY config config
COPY src src

RUN composer dump-autoload; \
    mkdir var; \
    chmod 777 var; \
    bin/console cache:warmup

#RUN rm -f /var/log/nginx/access.log;
     #rm -f /var/log/nginx/error.log

