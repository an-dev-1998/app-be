FROM php:8.3-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    libzip-dev \
    libonig-dev

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    zip \
    bcmath \
    fileinfo

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install -vvv --no-interaction

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000