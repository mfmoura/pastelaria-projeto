#!/bin/bash

# Verificar se o Laravel já está instalado
if [ ! -f /var/www/html/composer.json ]; then
    echo "Laravel não encontrado. Instalando..."

    # Instalar o Laravel
    composer create-project laravel/laravel .

    # Configurar permissões
    chown -R www-data:www-data /var/www/html
    chown -R $USER:www-data .
    chown -R 1000:1000 /var/www/html
    find . -type f -exec chmod 664 {} \;   
    find . -type d -exec chmod 775 {} \;
    
    echo "Laravel instalado com sucesso."
else
    echo "Laravel já instalado. Pulando instalação."
fi

# Executar o PHP-FPM
php-fpm