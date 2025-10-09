FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    supervisor \
    cron \
    && docker-php-ext-configure zip \
    && docker-php-ext-install -j$(nproc) zip mbstring pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install --no-scripts --no-interaction

COPY . .

RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

COPY docker/supervisord.conf /etc/supervisord.conf

COPY docker/laravel.cron /etc/cron.d/laravel

RUN chmod 0644 /etc/cron.d/laravel \
    && crontab /etc/cron.d/laravel

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
