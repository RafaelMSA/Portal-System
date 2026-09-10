FROM richarvey/nginx-php-fpm:latest

ENV WEBROOT=/var/www/html/public
ENV APP_ENV=production
ENV APP_DEBUG=false

COPY conf/nginx-site.conf /etc/nginx/sites-available/default.conf
COPY scripts/00-laravel-deploy.sh /etc/entrypoint.d/00-laravel-deploy.sh

RUN chmod +x /etc/entrypoint.d/00-laravel-deploy.sh

CMD ["/start.sh"]