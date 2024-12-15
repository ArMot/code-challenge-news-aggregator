FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xml

RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy existing application code
COPY . /var/www/html

# Set file permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 8000 and start PHP-FPM server
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

