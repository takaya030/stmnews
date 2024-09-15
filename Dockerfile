# composer 用
FROM composer:2 as build
WORKDIR /app
COPY composer.json composer.lock /app/
RUN composer install --no-dev

# Laravel の実行環境用のコンテナ
# development
FROM php:8.2-apache as devapp
RUN groupadd -g 1000 vagrant && useradd -u 1000 -g vagrant -m vagrant
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

EXPOSE 8080
COPY --from=build --chown=www-data:www-data /app /var/www/
COPY --chown=www-data:www-data . /var/www
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN chmod 777 -R /var/www/storage/ && \
    echo "Listen 8080" >> /etc/apache2/ports.conf && \
    echo "ServerName 127.0.0.1" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite

# prdapp
FROM devapp as prdapp
RUN rm -v /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    /usr/local/etc/php/conf.d/xdebug.ini
