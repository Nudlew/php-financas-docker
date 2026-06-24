#!/bin/bash
set -e

echo "Atualizando homologação..."
git pull

echo "Limpando referências antigas do compose..."
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml rm -sf app || true

echo "Buildando imagem da aplicação de homologação..."
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml build app

echo "Removendo container antigo da aplicação de homologação..."
docker rm -f php_financas_app_homolog 2>/dev/null || true

echo "Garantindo banco de homologação ativo..."
docker rm -f php_financas_db_homolog 2>/dev/null || true
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml up -d db

echo "Subindo nova aplicação de homologação..."
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml up -d --no-deps app

echo "Aplicando migrations em homologação..."
./aplicar-migrations-homolog.sh

echo "Homologação atualizada."
