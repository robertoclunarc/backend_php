FROM php:7-fpm
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN mkdir /var/lib/mysql && touch /var/lib/mysql/mysql.sock && chmod 777 /var/lib/mysql/mysql.sock
