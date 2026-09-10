FROM serversideup/php:8.4-fpm-nginx

COPY . /var/www/html
COPY scripts/00-laravel-deploy.sh /etc/entrypoint.d/00-laravel-deploy.sh

ENV PUBLIC_PATH=/var/www/html/public
ENV AUTORUN_ENABLED=true
ENV APP_ENV=production
ENV APP_DEBUG=false

RUN chmod +x /etc/entrypoint.d/00-laravel-deploy.sh \
	&& chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

CMD ["/init"]