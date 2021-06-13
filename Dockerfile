FROM php:5.5.38-apache
RUN apt-get update && apt-get install -y libicu-dev mariadb-client vim\
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl pdo pdo_mysql mysql mysqli \
&& docker-php-ext-enable mysqli

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime
RUN "date"

COPY ./ /var/www/html
RUN chown -R www-data:www-data .
RUN a2enmod headers
RUN a2enmod rewrite
RUN service apache2 restart
RUN php -m
