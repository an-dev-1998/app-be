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

# PHP extensions (QUAN TRỌNG)
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install deps
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000