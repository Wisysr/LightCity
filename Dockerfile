FROM php:8.2-apache

RUN composer require tecnickcom/tcpdf

# Установим зависимости и Composer
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install zip \
    && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Копируем проект
COPY . /var/www/html/

WORKDIR /var/www/html/

# Устанавливаем PHPWord
RUN composer require phpoffice/phpword

# Права
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
