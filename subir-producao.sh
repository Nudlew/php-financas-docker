#!/bin/bash
set -e

echo "Limpando ambiente de produção..."
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml down --remove-orphans || true
docker rm -f php_financas_db_prod php_financas_app_prod 2>/dev/null || true

echo "Subindo ambiente de produção..."
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml up -d --build --force-recreate

echo "Aguardando aplicação..."
sleep 5

echo "Instalando dependências..."
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml exec -T app composer install

echo "Produção disponível em: http://177.44.248.68:8080"
