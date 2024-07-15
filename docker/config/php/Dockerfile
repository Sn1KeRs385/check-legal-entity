FROM php:8.3.9-fpm AS base

RUN apt-get update && apt-get install -y \
        sudo \
        mc \
        nano \
        curl \
        libonig-dev

RUN apt-get update

RUN docker-php-ext-install mbstring iconv pdo pdo_mysql pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl;

RUN pecl install redis-5.3.7 \
	&& docker-php-ext-enable redis

RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir ~/.ssh/ && chmod 700 -R ~/.ssh/;

ENV NODE_VERSION=18.20.4
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"

CMD ["php-fpm"]

FROM base AS supervisor

RUN apt-get update && apt-get install -y supervisor

CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]

WORKDIR /etc/supervisor/conf.d/

FROM base AS cron

RUN curl -sL https://github.com/aptible/supercronic/releases/download/v0.1.12/supercronic-linux-amd64 -o /usr/local/bin/supercronic
RUN chmod +x /usr/local/bin/supercronic

CMD ["supercronic", "/etc/crontab"]
