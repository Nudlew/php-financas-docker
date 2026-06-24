#!/bin/bash
set -e

if [ ! -f .env.prod ]; then
  echo "Arquivo .env.prod não encontrado. Criando a partir de .env.prod.example..."
  cp .env.prod.example .env.prod
fi

echo "Limpando ambiente de produção..."
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml down --remove-orphans || true
docker rm -f php_financas_db_prod php_financas_app_prod 2>/dev/null || true

echo "Subindo ambiente de produção..."
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml up -d --build --force-recreate

echo "Aguardando aplicação..."
sleep 5

echo "Produção disponível em: http://177.44.248.68:8080"
