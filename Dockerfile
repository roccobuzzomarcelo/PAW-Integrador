# Imagen base con Apache y PHP
FROM php:8.4-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    && docker-php-ext-install pdo

# Copiamos el código al contenedor
COPY . .

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN cd /var/www/html && composer install

EXPOSE 8888

CMD ["php", "-S", "0.0.0.0:8888", "-t", "public"]