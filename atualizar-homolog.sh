#!/bin/bash
set -e

echo "Atualizando homologação..."
git pull
docker-compose -p financas_homolog --env-file .env.homolog -f docker-compose.homolog.yml up -d --build --force-recreate
./aplicar-migrations-homolog.sh
echo "Homologação atualizada."
