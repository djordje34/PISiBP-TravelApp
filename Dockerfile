FROM php:8.2-apache
#RUN useradd -ms /bin/bash /var/www/html
#RUN chown -R admin:admin /var/www/html
COPY www/slike_travel /var/www/html/slike_travel
COPY www/slikeGradova /var/www/html/slikeGradova
RUN chmod 755 /var/www/html
#USER admin
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

RUN pip install matplotlib
RUN pip install mysql-connector-python
RUN pip install selenium 
RUN pip install faker 
RUN pip install bs4 
RUN pip install requests 
RUN pip install pandas 
RUN pip install numpy 
RUN pip install openai 
RUN pip install translators 
RUN pip install bing_image_downloader 
RUN pip install googletrans
RUN pip install srtools
RUN docker-php-ext-install mysqli pdo pdo_mysql



RUN mkdir -p /scripts
COPY runit.sh /scripts
WORKDIR /scripts
RUN mkdir -p /pydata
RUN mkdir -p /pyscripts
COPY www/pyscripts /pyscripts
COPY www/pydata /pydata
RUN chmod +x runit.sh
RUN chmod 755 /scripts/runit.sh
RUN chmod 755 /pyscripts/populator.py
#RUN /scripts/./runit.sh
WORKDIR /
#ENTRYPOINT [ "scripts/runit.sh" ]
#COPY './phpdocker/php-fpm/php-ini-overrides.ini /etc/php/8.2/fpm/conf.d/99-overrides.ini';