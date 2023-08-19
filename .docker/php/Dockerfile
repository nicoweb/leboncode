FROM php:8.2-fpm

# Install system dependencies and PHP extensions required for Symfony and MySQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    wget \
    default-mysql-client \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl zip pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /var/lib/apt/lists/* # Clean up cache to reduce layer size

# Configure PHP
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create a non-root user to use when running the container
RUN groupadd -g 1000 devuser && \
    useradd -u 1000 -g devuser -m devuser && \
    passwd -d devuser && \
    adduser devuser sudo
USER devuser

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .
