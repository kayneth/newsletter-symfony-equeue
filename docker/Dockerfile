FROM php:7.2-apache


ENV SYMFONY_ENV=dev
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ARG AS_UID=1000

WORKDIR /var/www/html

RUN set -x \
	&& apt-get -q update \
    && apt-get install --no-install-recommends --no-install-suggests -qy \
        gnupg \
        apt-transport-https \
        cron \
        git \
        tzdata \
        supervisor \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libicu-dev \
        zip \
        unzip \
        dirmngr \
        libcurl4-gnutls-dev \
        librabbitmq-dev \
    && apt-key adv --keyserver "hkp://keyserver.ubuntu.com:80" --keyserver-options timeout=10 --recv-keys "573BFD6B3D8FBC641079A6ABABF5BD827BD9BF62" \
    && echo "deb http://nginx.org/packages/debian/ stretch nginx" >> /etc/apt/sources.list \
    && echo "deb-src http://nginx.org/packages/debian/ stretch nginx" >> /etc/apt/sources.list \
    && apt-get update \
    && apt-get install --no-install-recommends --no-install-suggests  -qy nginx \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && cp /usr/share/zoneinfo/Europe/Paris /etc/localtime && echo "Europe/Paris" > /etc/timezone \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd exif intl iconv fileinfo zip opcache pdo pdo_mysql pcntl curl bcmath sockets \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && usermod -u ${AS_UID} www-data \
    && chown ${AS_UID}:${AS_UID} /var/www \
    ;

USER www-data
RUN composer global require hirak/prestissimo

USER root
EXPOSE 80

COPY php.ini /usr/local/etc/php/