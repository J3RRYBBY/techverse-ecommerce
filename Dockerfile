
FROM php:8.4-fpm

WORKDIR /var/www

# System dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Configure GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# PHP extensions
RUN docker-php-ext-install -j"$(nproc)" \
    gd \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

# Copy application
COPY . .

# PHP dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# Frontend dependencies and build
RUN npm ci --include=optional
RUN npm run build

# Create required directories
RUN mkdir -p \
    public/categoryImage \
    public/productImage \
    public/category_images \
    public/userProfile \
    public/paymentReceipts \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Permissions
RUN chown -R www-data:www-data \
    public/categoryImage \
    public/productImage \
    public/category_images \
    public/userProfile \
    public/paymentReceipts \
    storage \
    bootstrap/cache \
    && chmod -R 775 \
    public/categoryImage \
    public/productImage \
    public/category_images \
    public/userProfile \
    public/paymentReceipts \
    storage \
    bootstrap/cache

# Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 10000

# Start services
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
