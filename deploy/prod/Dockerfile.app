############################################
# ---------- 1) Vendor (Composer) ----------
# Solo dependencias => máximo caché
############################################
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
  --no-dev \
  --prefer-dist \
  --no-interaction \
  --no-progress \
  --no-scripts \
  --optimize-autoloader


############################################
# ---------- 2) Vite build (Node) ----------
############################################
FROM node:22-alpine AS assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build


############################################
# ---------- 3) Build (PHP CLI) ----------
# Copia código + vendor y ejecuta scripts Laravel
############################################
FROM php:8.5-cli-alpine AS build
WORKDIR /app

# Dependencias mínimas + toolchain para compilar extensiones
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    icu-dev oniguruma-dev libzip-dev zip unzip \
  && docker-php-ext-install intl mbstring zip \
  && apk del \
    $PHPIZE_DEPS \
    icu-dev oniguruma-dev libzip-dev

# Composer binario
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Código completo (incluye app/Support/*)
COPY . .

# Vendor ya instalado (sin scripts)
COPY --from=vendor /app/vendor /app/vendor

# Autoload final + package discovery (con el código presente)
RUN composer dump-autoload --no-dev --optimize \
 && php artisan package:discover --ansi


############################################
# ---------- 4) Runtime (php-fpm) ----------
############################################
FROM php:8.5-fpm-alpine AS app
WORKDIR /var/www/html

# System deps + toolchain + extensiones PHP para Laravel
# (NO compilar opcache: se habilita)
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    icu-dev oniguruma-dev libzip-dev zip unzip \
    freetype-dev libjpeg-turbo-dev libpng-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo_mysql intl mbstring zip gd \
  && docker-php-ext-enable opcache \
  && apk del \
    $PHPIZE_DEPS \
    icu-dev oniguruma-dev libzip-dev freetype-dev libjpeg-turbo-dev libpng-dev

# Opcache INI
#COPY docker/prod/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY deploy/prod/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini


# Copiar app + vendor ya “finalizados” (autoload + discover)
COPY --from=build /app /var/www/html

# Copiar build final (manifest + assets)
COPY --from=assets /app/public/build /var/www/html/public/build

# Permisos Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
  && chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm", "-F"]


############################################
# ---------- 5) Runtime (cli) --------------
# Target opcional para el worker
############################################
FROM app AS cli
CMD ["php", "-v"]
