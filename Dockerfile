FROM richarvey/nginx-php-fpm:latest

COPY . /var/www/html
COPY conf/nginx-site.conf /etc/nginx/sites-available/default.conf

ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false

CMD ["/start.sh"]