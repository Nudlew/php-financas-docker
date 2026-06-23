#!/bin/bash
set -e

echo "Subindo ambiente de produção..."

docker-compose --env-file .env.prod -f docker-compose.prod.yml up -d --build

echo "Aguardando container da aplicação..."
sleep 5

echo "Instalando dependências Composer..."
docker-compose --env-file .env.prod -f docker-compose.prod.yml exec -T app composer install

echo "Produção disponível em: http://177.44.248.68:8080"
