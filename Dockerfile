FROM docker.listcloud.cn:5000/nginx-php7
COPY . /var/www/html
COPY ./devops/config/default.conf /etc/nginx/nginx.conf
COPY ./devops/config/vhost.conf /etc/nginx/conf.d/vhost.conf
COPY ./devops/config/mime.types /etc/nginx/mime.types
RUN chown -R application.application /var/www/html/storage
RUN chmod -R 777 /var/www/html/storage/logs
WORKDIR /var/www/html

