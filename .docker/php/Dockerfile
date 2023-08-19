FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configure PHP
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install Symfony CLI
RUN apt-get install -y wget \
    && curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Copy application files
WORKDIR /var/www/html
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 devuser && \
    useradd -u 1000 -g devuser -m devuser && \
    passwd -d devuser && \
    adduser devuser sudo
USER devuser

# Copy application files
WORKDIR /var/www/html
COPY . .
