#!/bin/bash
set -e

echo "Aplicando migrations em homologação..."

docker exec -i php_financas_db_homolog psql -U postgres -d financas_homolog < db/migrations/V000__controle_migrations.sql

for arquivo in db/migrations/V*.sql; do
  versao=$(basename "$arquivo")
  if [ "$versao" != "V000__controle_migrations.sql" ]; then
    existe=$(docker exec -i php_financas_db_homolog psql -U postgres -d financas_homolog -tAc "SELECT 1 FROM schema_migrations WHERE versao = '$versao'")

    if [ "$existe" != "1" ]; then
      echo "Aplicando $versao"
      docker exec -i php_financas_db_homolog psql -U postgres -d financas_homolog < "$arquivo"
      docker exec -i php_financas_db_homolog psql -U postgres -d financas_homolog -c "INSERT INTO schema_migrations (versao) VALUES ('$versao');"
    else
      echo "$versao já aplicada"
    fi
  fi
done

echo "Migrations de homologação concluídas."
