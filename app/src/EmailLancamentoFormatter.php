<?php

namespace App;

class EmailLancamentoFormatter
{
    public function assuntoInclusao(): string
    {
        return "Novo lançamento incluído no sistema";
    }

    public function assuntoAlteracao(): string
    {
        return "Lançamento alterado no sistema";
    }

    public function mensagemInclusao(array $dados, int $id): string
    {
        return "Um novo lançamento foi cadastrado no sistema.\n\n"
            . "ID: {$id}\n"
            . "Descrição: {$dados['descricao']}\n"
            . "Data: {$dados['data_lancamento']}\n"
            . "Valor: {$dados['valor']}\n"
            . "Tipo: {$dados['tipo_lancamento']}\n"
            . "Situação: {$dados['situacao']}\n";
    }

    public function mensagemAlteracao(array $dados, int $id): string
    {
        return "Um lançamento foi alterado no sistema.\n\n"
            . "ID: {$id}\n"
            . "Descrição: {$dados['descricao']}\n"
            . "Data: {$dados['data_lancamento']}\n"
            . "Valor: {$dados['valor']}\n"
            . "Tipo: {$dados['tipo_lancamento']}\n"
            . "Situação: {$dados['situacao']}\n";
    }
}
