FROM php:8.2-apache

# Включаем модуль PHP и настраиваем права
RUN docker-php-ext-install mysqli && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

COPY . /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]
