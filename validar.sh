#!/bin/bash
set -e

AMBIENTE=${1:-homolog}

if [ "$AMBIENTE" = "homolog" ]; then
  PROJETO="financas_homolog"
  ENV_FILE=".env.homolog"
  COMPOSE_FILE="docker-compose.homolog.yml"
elif [ "$AMBIENTE" = "prod" ]; then
  PROJETO="financas_prod"
  ENV_FILE=".env.prod"
  COMPOSE_FILE="docker-compose.prod.yml"
else
  echo "Uso: ./validar.sh [homolog|prod]"
  exit 1
fi

echo "Executando validações no ambiente: $AMBIENTE"
mkdir -p reports

echo "1. Rodando testes automatizados..."
docker-compose -p "$PROJETO" --env-file "$ENV_FILE" -f "$COMPOSE_FILE" exec -T app \
  php /var/www/html/vendor/bin/phpunit \
  --testdox \
  --log-junit /var/www/html/reports/phpunit-junit.xml | tee "reports/phpunit-output-$AMBIENTE.txt"

echo "2. Rodando PHPCS..."
docker-compose -p "$PROJETO" --env-file "$ENV_FILE" -f "$COMPOSE_FILE" exec -T app \
  ./vendor/bin/phpcs --standard=phpcs.xml src tests | tee "reports/phpcs-output-$AMBIENTE.txt"

echo "3. Rodando PHPStan..."
docker-compose -p "$PROJETO" --env-file "$ENV_FILE" -f "$COMPOSE_FILE" exec -T app \
  ./vendor/bin/phpstan analyse --configuration=phpstan.neon | tee "reports/phpstan-output-$AMBIENTE.txt"

echo "Validação concluída com sucesso."
echo "Relatórios gerados na pasta reports/"
