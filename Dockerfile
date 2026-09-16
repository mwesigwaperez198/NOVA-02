FROM php:8.2-cli

# Install dependencies: PDO MySQL + OpenSSL for Aiven SSL
RUN apt-get update && apt-get install -y libssl-dev && \
    docker-php-ext-install pdo pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .

EXPOSE 10000

# Shell form so $PORT variable expands correctly at runtime
CMD php -S 0.0.0.0:${PORT:-10000} -t .
