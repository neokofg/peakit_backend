FROM php:8.2.0
RUN apt-get update && apt-get install -y \
                          libpq-dev \
                          libwebp-dev \
                          libjpeg62-turbo-dev \
                          libpng-dev libxpm-dev \
                          libfreetype6-dev \
    && docker-php-ext-configure gd \
           --with-webp \
           --with-jpeg \
           --with-freetype \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install \
      pcntl
RUN apt-get install -y gcc g++ autoconf
RUN pecl install swoole && docker-php-ext-enable swoole
COPY . /
WORKDIR /
CMD ["php", "artisan", "octane:start", "--host=0.0.0.0"]
