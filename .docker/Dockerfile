# Use uma imagem base oficial do PHP 8.2
FROM php:8.2-fpm

# Instale dependências necessárias e extensões PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libonig-dev \
    libxml2-dev \
    libbz2-dev \
    libtidy-dev \
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
    tokenizer \
    xml \
    opcache \
    curl \
    tidy \
    bz2

# Instale o Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Ajuste permissões para diretórios necessários
RUN chown -R www-data:www-data /var/www/html

# Exponha a porta 9000
EXPOSE 9000

# Comando padrão ao iniciar o contêiner
CMD ["php-fpm"]