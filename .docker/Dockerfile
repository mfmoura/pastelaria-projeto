# Use uma imagem base oficial do PHP 8.2
FROM php:8.2-fpm

# Instale dependências necessárias e extensões PHP
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    unzip \
    git \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libonig-dev \
    libxml2-dev \
    libbz2-dev \
    libtidy-dev \
    libcurl4-openssl-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install \
    gd \
    zip \
    pdo \
    pdo_mysql \
    mysqli \
    bcmath \
    mbstring \
    xml \
    opcache \
    curl \
    tidy \
    bz2

# Instale o Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie o script de setup
COPY ./.docker/setup.sh /usr/local/bin/setup.sh

# Torne o script executável
RUN chmod +x /usr/local/bin/setup.sh

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Exponha a porta 9000
EXPOSE 9000

# Use o script de setup como comando inicial
CMD ["sh", "/usr/local/bin/setup.sh"]
