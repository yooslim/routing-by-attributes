# Use PHP-FPM Alpine image
FROM php:fpm-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache git

# Set working directory inside the container
WORKDIR /var/www/html

# Copy composer.json and composer.lock for dependency installation
COPY composer*.json ./

# Install dependencies
RUN composer install

# Copy the rest of the project
COPY . .

# Expose the desired port for PHP-FPM
EXPOSE 9000

# Default command (optional)
CMD ["php-fpm"]