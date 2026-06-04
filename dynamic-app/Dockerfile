FROM php:apache

# Install mysqli extension for database connections
RUN docker-php-ext-install mysqli

# Copy application files to Apache root
COPY . /var/www/html/

# Ensure uploads directory exists and is writable by Apache
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 775 /var/www/html/uploads

# Expose port 80
EXPOSE 80
