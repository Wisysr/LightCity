FROM php:8.2-apache

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install zip

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Копирование файлов
COPY . /var/www/html/
WORKDIR /var/www/html/

# Установка TCPDF
RUN composer require tecnickcom/tcpdf

# Права
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
