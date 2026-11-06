#!/bin/bash
set -e

echo "=== [BDSoftcom] Inicializando Ambiente Oficina Laravel (PHP 7.2) ==="

# Aguardar o banco de dados MySQL inicializar
echo "Aguardando banco de dados bdsoftcom_db estar pronto..."
while ! mysqladmin ping -h"bdsoftcom_db" -u"bdsoftcom_user" -p"bdsoftcom_pass" --silent; do
    sleep 2
done
echo "Banco de dados bdsoftcom_db pronto e conectado!"

# Instalar dependências se vendor não existir
if [ ! -d "vendor" ]; then
    echo "Instalando dependencias do Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Gerar chave da aplicação caso não exista
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Gerando APP_KEY do Laravel..."
    php artisan key:generate --force
fi

# Ajustar permissões de escrita
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Executar migrações e seeders
echo "Executando migrações e seeders no banco bdsoftcom_oficina..."
php artisan migrate --force
php artisan db:seed --force

echo "=== [BDSoftcom] Oficina Laravel pronta para uso! ==="
exec php-fpm
