#!/bin/bash
set -e

echo "Limpando ambiente de homologação..."
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml down --remove-orphans || true
docker rm -f 6526bd48ea6e_php_financas_db php_financas_db php_financas_db_homolog php_financas_app_homolog 2>/dev/null || true
docker volume rm php-financas-docker_postgres_data_homolog 2>/dev/null || true

echo "Subindo ambiente de homologação..."
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml up -d --build --force-recreate

echo "Aguardando aplicação..."
sleep 5

echo "Instalando dependências..."
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml exec -T app composer install

echo "Homologação disponível em: http://177.44.248.68:8081"
