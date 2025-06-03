FROM php:8.3-apache


RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd

RUN a2enmod rewrite

COPY . /var/www/html/

RUN git config --global --add safe.directory /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["apache2-foreground"]
