<?php

namespace App;

class FiltroLancamento
{
    public function construir(array $filtros): array
    {
        $where = [];
        $params = [];

        if (!empty($filtros['data_inicio'])) {
            $where[] = "data_lancamento >= :data_inicio";
            $params[':data_inicio'] = $filtros['data_inicio'];
        }

        if (!empty($filtros['data_fim'])) {
            $where[] = "data_lancamento <= :data_fim";
            $params[':data_fim'] = $filtros['data_fim'];
        }

        if (!empty($filtros['situacao'])) {
            $where[] = "situacao = :situacao";
            $params[':situacao'] = $filtros['situacao'];
        }

        $sql = "SELECT * FROM lancamento";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY data_lancamento DESC, id DESC";

        return [
            'sql' => $sql,
            'params' => $params
        ];
    }
}
