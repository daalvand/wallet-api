FROM php:8.2-fpm

ARG UID=1000
ARG GID=$UID
RUN groupmod -g $GID www-data
RUN usermod -u $UID www-data
WORKDIR /var/www/html

RUN apt-get update
RUN apt-get install -y libzip-dev zip default-mysql-client

# Install the PHP PDO MySQL extension
RUN docker-php-ext-install pdo_mysql zip

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir -p /var/www/.composer && chown www-data:www-data -R /var/www/.composer

#install dependencies
USER www-data
COPY --chown=www-data:www-data ./ ./
