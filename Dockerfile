RUN apt-get update && apt-get install -y unzip git \
    && docker-php-ext-install mysqli \
    && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html/

RUN composer require phpoffice/phpword
