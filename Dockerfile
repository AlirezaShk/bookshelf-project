FROM php:8.0

RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install \
        pdo_mysql \
        zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /app
COPY . /app
RUN composer install

CMD php artisan serve --host=localhost --port=8000
EXPOSE 8000