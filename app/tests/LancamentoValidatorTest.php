<?php

namespace Tests;

use App\LancamentoValidator;
use PHPUnit\Framework\TestCase;

class LancamentoValidatorTest extends TestCase
{
    private LancamentoValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new LancamentoValidator();
    }

    public function testDescricaoValidaComTextoCorreto(): void
    {
        $this->assertTrue($this->validator->descricaoValida('Salário Mensal'));
    }

    public function testDescricaoInvalidaQuandoVazia(): void
    {
        $this->assertFalse($this->validator->descricaoValida(''));
    }

    public function testDescricaoInvalidaQuandoMuitoCurta(): void
    {
        $this->assertFalse($this->validator->descricaoValida('ab'));
    }

    public function testDataValidaComDataReal(): void
    {
        $this->assertTrue($this->validator->dataValida('2026-04-16'));
    }

    public function testDataInvalidaComDiaImpossivel(): void
    {
        $this->assertFalse($this->validator->dataValida('2026-02-31'));
    }

    public function testValorValidoQuandoMaiorQueZero(): void
    {
        $this->assertTrue($this->validator->valorValido(150.75));
    }

    public function testValorInvalidoQuandoZero(): void
    {
        $this->assertFalse($this->validator->valorValido(0));
    }

    public function testTipoValidoReceita(): void
    {
        $this->assertTrue($this->validator->tipoValido('RECEITA'));
    }

    public function testTipoInvalidoForaDoPadrao(): void
    {
        $this->assertFalse($this->validator->tipoValido('ENTRADA'));
    }

    public function testLancamentoCompletoValido(): void
    {
        $dados = [
            'descricao' => 'Freelance',
            'data_lancamento' => '2026-04-16',
            'valor' => 500,
            'tipo_lancamento' => 'RECEITA',
            'situacao' => 'ATIVO'
        ];

        $this->assertTrue($this->validator->lancamentoValido($dados));
    }
}
