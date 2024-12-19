#!/bin/bash

# Verificar se o Laravel já está instalado
if [ ! -f /var/www/html/composer.json ]; then
    echo "Laravel não encontrado. Instalando..."

    # Instalar o Laravel
    composer create-project laravel/laravel .

    # Configurar permissões gerais
    chown -R www-data:www-data /var/www/html
    chown -R $USER:www-data .
    chown -R 1000:1000 /var/www/html

    # Configurar permissões específicas para arquivos e diretórios
    find storage bootstrap/cache -type f -exec chmod 664 {} \;
    find storage bootstrap/cache -type d -exec chmod 775 {} \;

    echo "Laravel instalado com sucesso."
else
    echo "Laravel já instalado. Pulando instalação."
fi

# Executar o PHP-FPM
php-fpm
