FROM php:php:8.1-rc-fpm
ENV DEBIAN_FRONTEND=noninteractive

COPY docker/php/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

# Download script to install PHP extensions and dependencies
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

RUN apt-get update && \
    apt-get install -y \
    curl \
    apt-transport-https \
    software-properties-common

RUN DEBIAN_FRONTEND=noninteractive apt-get update -q \
    && DEBIAN_FRONTEND=noninteractive apt-get install -qq -y \
    curl \
    git \
    zip unzip \
    && install-php-extensions \
    bcmath \
    bz2 \
    calendar \
    exif \
    gd \
    intl \
    ldap \
    memcached \
    mysqli \
    opcache \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    redis \
    soap \
    xsl \
    zip \
    sockets \
    pdo_sqlsrv \
    sqlsrv
# already installed:
#      iconv \
#      mbstring \
# RUN apt-get install -y nodejs
RUN apt-get update && apt-get install -y gnupg
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install -y yarn

#Install symfony bin
# RUN curl -sS https://get.symfony.com/cli/installer -O - | bash && \
# mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -s $(composer config --global home) /root/composer
ENV PATH=$PATH:/root/composer/vendor/bin COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 9000

WORKDIR /var/www/symfony

RUN apt-get update && apt-get install -y supervisor
RUN mkdir -p /var/log/supervisor
COPY docker/php/messenger-worker.conf /etc/supervisor/conf.d/messenger-worker.conf
RUN echo user=root >>  /etc/supervisor/supervisord.conf

RUN apt-get -y update && curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp && chmod a+rx /usr/local/bin/yt-dlp
RUN apt-get -y update && apt-get -y upgrade && apt-get install -y ffmpeg
# CMD ["/usr/bin/supervisord", "-n"]
