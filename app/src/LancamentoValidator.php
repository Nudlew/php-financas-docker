<?php

namespace App;

class LancamentoValidator
{
    public function descricaoValida(string $descricao): bool
    {
        return trim($descricao) !== '' && mb_strlen(trim($descricao)) >= 3;
    }

    public function dataValida(string $data): bool
    {
        $partes = explode('-', $data);

        if (count($partes) !== 3) {
            return false;
        }

        [$ano, $mes, $dia] = $partes;

        return checkdate((int)$mes, (int)$dia, (int)$ano);
    }

    public function valorValido($valor): bool
    {
        return is_numeric($valor) && (float)$valor > 0;
    }

    public function tipoValido(string $tipo): bool
    {
        return in_array($tipo, ['RECEITA', 'DESPESA'], true);
    }

    public function situacaoValida(string $situacao): bool
    {
        return in_array($situacao, ['ATIVO', 'INATIVO'], true);
    }

    public function lancamentoValido(array $dados): bool
    {
        return $this->descricaoValida($dados['descricao'] ?? '')
            && $this->dataValida($dados['data_lancamento'] ?? '')
            && $this->valorValido($dados['valor'] ?? null)
            && $this->tipoValido($dados['tipo_lancamento'] ?? '')
            && $this->situacaoValida($dados['situacao'] ?? '');
    }
}
