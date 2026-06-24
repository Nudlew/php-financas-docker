#!/bin/bash
set -e

if [ ! -f .env.homolog ]; then
  echo "Arquivo .env.homolog não encontrado. Criando a partir de .env.homolog.example..."
  cp .env.homolog.example .env.homolog
fi

echo "Limpando ambiente de homologação..."
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml down --remove-orphans || true
docker rm -f php_financas_db_homolog php_financas_app_homolog 2>/dev/null || true

echo "Subindo ambiente de homologação..."
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml up -d --build --force-recreate

echo "Aguardando aplicação..."
sleep 5

echo "Homologação disponível em: http://177.44.248.68:8081"
