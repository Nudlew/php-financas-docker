#!/bin/bash
set -e

echo "Atualizando produção..."
git pull
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml up -d --build
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml exec -T app composer install
./aplicar-migrations-producao.sh
echo "Produção atualizada."
