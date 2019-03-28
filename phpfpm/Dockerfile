FROM php:fpm
#COPY entrypoint.sh /entrypoint.sh

# install composer
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer
RUN apt-get update \
&& apt-get install -y \
git \
zip \
unzip \
vim

RUN apt-get update \
    && apt-get install -y libpq-dev gnupg gnupg2 gnupg1\
    && docker-php-ext-install pdo_mysql pdo_pgsql \
    mbstring tokenizer ctype json bcmath

# install node.js 10.x
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt-get install -y nodejs
RUN npm install

WORKDIR /var/www/laravel_app

RUN composer require guzzlehttp/guzzle
RUN php artisan storage:link
