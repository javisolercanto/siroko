FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip curl zip libzip-dev libicu-dev libonig-dev libpq-dev libxml2-dev sudo \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

RUN useradd -m -s /bin/bash -u 1000 vscode \
    && echo "vscode ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

USER vscode
WORKDIR /app