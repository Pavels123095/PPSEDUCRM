FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    sqlite-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_sqlite zip intl mbstring

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./
RUN composer install --no-interaction --prefer-dist

COPY . .
RUN composer dump-autoload --optimize \
    && chmod +x docker-entrypoint.sh \
    && cp .env.example .env \
    && php artisan key:generate --force

EXPOSE 8000
ENTRYPOINT ["./docker-entrypoint.sh"]
