#!/bin/bash
set -e

echo "Atualizando ambiente de homologação..."

git pull
docker-compose --env-file .env.homolog -f docker-compose.homolog.yml up -d --build
docker-compose --env-file .env.homolog -f docker-compose.homolog.yml exec -T app composer install

echo "Homologação atualizada com sucesso."
