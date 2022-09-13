FROM php:8.1.8-fpm

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    libfreetype6-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libxslt-dev \
    libicu-dev \
    libgmp-dev \
    libmcrypt-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libldap2-dev \
    libmagickwand-dev

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-webp=/usr/include/ --with-jpeg=/usr/include/ && \
docker-php-ext-install bcmath gd iconv intl mbstring pcntl mysqli pdo_mysql zip \
    && pecl install imagick && docker-php-ext-enable imagick

RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer  --install-dir=/usr/local/bin && \
    echo "alias composer='composer'" >> /root/.bashrc && composer

RUN sed -i -e 's/listen.*/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.conf

WORKDIR /var/www
