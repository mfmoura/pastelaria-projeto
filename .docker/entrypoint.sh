#!/bin/bash

if [ ! -d "vendor" ]; then
    echo "Diretório 'vendor' não encontrado. Executando 'composer install'..."
    composer install
fi

if [ ! -f ".env" ]; then
    echo "Arquivo '.env' não encontrado. Copiando '.env.example' para '.env'..."
    cp .env.example .env

    echo "Gerando a chave da aplicação..."
    php artisan key:generate
    php artisan session:table
    php artisan migrate
fi

echo "Iniciando PHP-FPM..."
php-fpm
