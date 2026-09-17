FROM php:8.2-fpm

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
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

# Install PHP extensions
RUN docker-php-ext-install \
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

# Install Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Copy Laravel project
COPY . .

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# Install frontend dependencies
RUN npm ci --include=optional

# Fix Rollup Linux native dependency
RUN npm install @rollup/rollup-linux-x64-gnu --save-dev --force

# Build Vite
RUN npm run build

# Laravel permissions
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

RUN chown -R www-data:www-data \
    public/categoryImage \
    public/productImage \
    public/category_images \
    public/userProfile \
    public/paymentReceipts \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    public/categoryImage \
    public/productImage \
    public/category_images \
    public/userProfile \
    public/paymentReceipts \
    storage \
    bootstrap/cache

# Nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 10000

# Start Laravel
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate --force && php artisan db:seed --force && php-fpm -D && nginx -g 'daemon off;'"]
