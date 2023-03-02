FROM python:3.11

EXPOSE 3306
EXPOSE 8000
EXPOSE 8001

RUN apt-get update && apt-get install -y --no-install-recommends \
    python3 \
    python3-pip \
    && \
apt-get clean && \
rm -rf /var/lib/apt/lists/*




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
RUN echo './conf/my.cnf' > '/etc/mysql/my.cnf'

WORKDIR  /var/www/html/

RUN mkdir pyscripts
RUN mkdir slike_travel
RUN mkdir slikeGradova
RUN mkdir pydata

RUN chmod 777 /var/www/html/
COPY ./www/pydata ./pydata 
COPY ./www/pyscripts ./pyscripts
COPY ./www/slike_travel ./slike_travel
COPY ./www/slikeGradova ./slikeGradova