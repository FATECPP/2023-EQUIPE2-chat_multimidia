FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y git libcurl4-openssl-dev pkg-config libssl-dev \
    && rm -rf /var/lib/apt/lists/*
    
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

RUN apt-get update -y && apt-get upgrade && pecl install mongodb && docker-php-ext-enable mongodb
RUN a2enmod rewrite
RUN composer update && composer install

EXPOSE 80
EXPOSE 9090

RUN echo "<Directory /var/www/html/uploads/>" >> /etc/apache2/apache2.conf
RUN echo "Options +Indexes" >> /etc/apache2/apache2.conf
RUN echo "AllowOverride All" >> /etc/apache2/apache2.conf
RUN echo "</Directory>" >> /etc/apache2/apache2.conf

CMD ["bash", "-c", "apache2-foreground & php bin/server.php"]