FROM php:8.2-apache
COPY www/slike_travel /var/www/html/slike_travel
COPY www/slikeGradova /var/www/html/slikeGradova
COPY www/pyscripts /var/www/html/pyscripts
RUN chmod 755 /var/www/html
RUN chmod 755 /var/www/html/pyscripts/populator.py
RUN chmod 755 /var/www/html/pyscripts/generator.py
RUN docker-php-ext-install mysqli
RUN apt-get update && apt-get install -y --no-install-recommends \
    python3 \
    python3-pip \
    && \
apt-get clean && \
rm -rf /var/lib/apt/lists/*


EXPOSE 3306
EXPOSE 8000
EXPOSE 8001
RUN docker-php-ext-install mysqli pdo pdo_mysql
#COPY './phpdocker/php-fpm/php-ini-overrides.ini /etc/php/8.2/fpm/conf.d/99-overrides.ini';