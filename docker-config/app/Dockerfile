# =========================
# 1) Frontend build stage
# =========================
FROM node:20-alpine AS frontend

WORKDIR /var/www

COPY package.json package-lock.json* ./
RUN npm ci 2>/dev/null || npm install

COPY resources/ resources/
COPY vite.config.js ./
COPY public/ public/

ENV NODE_ENV=production
RUN npm run build

# =========================
# 2) PHP App stage
# =========================
FROM php:8.5-fpm

# Sistem paketlerini yükleme
RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl unzip zip \
    libpng-dev libonig-dev libxml2-dev \
    libzip-dev \
  && rm -rf /var/lib/apt/lists/*

# Redis extension (phpredis)
RUN pecl install redis \
 && docker-php-ext-enable redis

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer kurulumu
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY --from=frontend /var/www/public/build /var/www/public/build

RUN chown -R www-data:www-data /var/www

USER www-data
