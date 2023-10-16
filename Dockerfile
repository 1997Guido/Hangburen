FROM php:8.2-apache
RUN apt-get update && docker-php-ext-install pdo_mysql
RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/
