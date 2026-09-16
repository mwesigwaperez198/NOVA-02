FROM php:8.2-cli

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Expose port (Render injects $PORT at runtime)
EXPOSE 10000

# Start PHP built-in server on Render's dynamic port
CMD php -S 0.0.0.0:${PORT:-10000} -t .
