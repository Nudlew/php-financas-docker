#!/bin/bash
set -e

echo "Atualizando produção..."
git pull

echo "Removendo referências antigas do app..."
docker rm -f php_financas_app_prod 2>/dev/null || true
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml rm -sf app || true

echo "Buildando imagem..."
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml build app

echo "Subindo aplicação..."
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml up -d --no-deps app

echo "Aplicando migrations..."
./aplicar-migrations-producao.sh

echo "Produção atualizada."
