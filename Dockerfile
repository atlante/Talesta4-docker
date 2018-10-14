# PHP 5.6 avec apache pour talesta4
FROM php:5.6-apache
MAINTAINER agentcobra <agentcobra@free.fr>
LABEL maintainer="agentcobra@free.fr"


RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y git vim

RUN a2enmod rewrite
RUN sed -i "/<\/VirtualHost>/i<Directory \"\/\">\nOptions FollowSymLinks\nAllowOverride all\n<\/Directory>" /etc/apache2/sites-enabled/000-default.conf
RUN service apache2 restart

RUN git clone https://github.com/atlante/Talesta4-docker.git .
#ADD cake cake
#ADD plugins plugins
#ADD vendors vendors
#ADD .htaccess .htaccess
#ADD index.php index.php

VOLUME /var/www/html/lieux/

#RUN chmod 777 app/tmp

#SHELL ["cake/console/cake"]
#RUN console
