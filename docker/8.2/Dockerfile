FROM php:8.2-fpm-alpine

RUN curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions gd  \
    xdebug  \
    bcmath  \
    zip  \
    intl  \
    redis  \
    igbinary  \
    @composer

COPY php.ini /usr/local/etc/php/

EXPOSE 9000
CMD ["php-fpm"]
