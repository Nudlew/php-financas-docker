#!/bin/bash
set -e

echo "Atualizando ambiente de produção..."

git pull
docker-compose --env-file .env.prod -f docker-compose.prod.yml up -d --build
docker-compose --env-file .env.prod -f docker-compose.prod.yml exec -T app composer install

echo "Produção atualizada com sucesso."
