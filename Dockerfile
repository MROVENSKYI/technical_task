FROM php:8.2-cli

WORKDIR /var/www/html

COPY ./ /var/www/html/

RUN apt-get update && \
    apt-get install -y wget git unzip libzip-dev && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip bcmath && \
    wget -O composer-setup.php https://getcomposer.org/installer && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php && \
    apt-get purge -y wget && \
    apt-get autoremove -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN composer install --prefer-dist

CMD ["php", "script.php", "input.csv"]