<?php

namespace Tests;

use App\EmailLancamentoFormatter;
use PHPUnit\Framework\TestCase;

class EmailLancamentoFormatterTest extends TestCase
{
    private EmailLancamentoFormatter $formatter;

    protected function setUp(): void
    {
        $this->formatter = new EmailLancamentoFormatter();
    }

    public function testAssuntoInclusaoCorreto(): void
    {
        $this->assertEquals(
            'Novo lançamento incluído no sistema',
            $this->formatter->assuntoInclusao()
        );
    }

    public function testAssuntoAlteracaoCorreto(): void
    {
        $this->assertEquals(
            'Lançamento alterado no sistema',
            $this->formatter->assuntoAlteracao()
        );
    }

    public function testMensagemInclusaoContemDescricaoEId(): void
    {
        $dados = [
            'descricao' => 'Salário',
            'data_lancamento' => '2026-04-16',
            'valor' => '5000.00',
            'tipo_lancamento' => 'RECEITA',
            'situacao' => 'ATIVO'
        ];

        $mensagem = $this->formatter->mensagemInclusao($dados, 10);

        $this->assertStringContainsString('ID: 10', $mensagem);
        $this->assertStringContainsString('Descrição: Salário', $mensagem);
    }

    public function testMensagemAlteracaoContemTipoESituacao(): void
    {
        $dados = [
            'descricao' => 'Internet',
            'data_lancamento' => '2026-04-16',
            'valor' => '120.00',
            'tipo_lancamento' => 'DESPESA',
            'situacao' => 'INATIVO'
        ];

        $mensagem = $this->formatter->mensagemAlteracao($dados, 5);

        $this->assertStringContainsString('Tipo: DESPESA', $mensagem);
        $this->assertStringContainsString('Situação: INATIVO', $mensagem);
    }
}
