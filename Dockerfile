FROM wyveo/nginx-php-fpm

RUN mkdir -p /var/log; \
    mkdir /application; \
    apt-get update && \
    apt-get install -y \
        make \
        git \
        httpie \
        curl; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    apt-get -y autoclean

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
    mkdir var /tmpfs; \
    chmod 777 var; \
    bin/console cache:warmup

#RUN rm -f /var/log/nginx/access.log;
     #rm -f /var/log/nginx/error.log

