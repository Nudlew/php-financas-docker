#!/bin/bash
set -e

echo "Atualizando produção..."
git pull
docker-compose -p financas_prod --env-file .env.prod -f docker-compose.prod.yml up -d --build --force-recreate
./aplicar-migrations-producao.sh
echo "Produção atualizada."
