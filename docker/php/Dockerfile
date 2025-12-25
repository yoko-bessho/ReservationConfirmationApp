FROM php:8.1-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \

  && docker-php-ext-install pdo_mysql zip \
  && docker-php-ext-install gd \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && apt clean \
  && rm -rf /var/lib/apt/lists/*


RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www
