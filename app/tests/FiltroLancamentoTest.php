<?php

namespace Tests;

use App\FiltroLancamento;
use PHPUnit\Framework\TestCase;

class FiltroLancamentoTest extends TestCase
{
    private FiltroLancamento $filtro;

    protected function setUp(): void
    {
        $this->filtro = new FiltroLancamento();
    }

    public function testSemFiltrosRetornaSqlBase(): void
    {
        $resultado = $this->filtro->construir([]);

        $this->assertStringContainsString('SELECT * FROM lancamento', $resultado['sql']);
        $this->assertStringContainsString('ORDER BY data_lancamento DESC, id DESC', $resultado['sql']);
        $this->assertEmpty($resultado['params']);
    }

    public function testFiltroPorDataInicio(): void
    {
        $resultado = $this->filtro->construir([
            'data_inicio' => '2026-04-01'
        ]);

        $this->assertStringContainsString('data_lancamento >= :data_inicio', $resultado['sql']);
        $this->assertEquals('2026-04-01', $resultado['params'][':data_inicio']);
    }

    public function testFiltroPorDataFim(): void
    {
        $resultado = $this->filtro->construir([
            'data_fim' => '2026-04-30'
        ]);

        $this->assertStringContainsString('data_lancamento <= :data_fim', $resultado['sql']);
        $this->assertEquals('2026-04-30', $resultado['params'][':data_fim']);
    }

    public function testFiltroPorSituacao(): void
    {
        $resultado = $this->filtro->construir([
            'situacao' => 'ATIVO'
        ]);

        $this->assertStringContainsString('situacao = :situacao', $resultado['sql']);
        $this->assertEquals('ATIVO', $resultado['params'][':situacao']);
    }

    public function testFiltroComTodosOsParametros(): void
    {
        $resultado = $this->filtro->construir([
            'data_inicio' => '2026-04-01',
            'data_fim' => '2026-04-30',
            'situacao' => 'ATIVO'
        ]);

        $this->assertStringContainsString('data_lancamento >= :data_inicio', $resultado['sql']);
        $this->assertStringContainsString('data_lancamento <= :data_fim', $resultado['sql']);
        $this->assertStringContainsString('situacao = :situacao', $resultado['sql']);
        $this->assertCount(3, $resultado['params']);
    }

    public function testSqlComFiltrosContemWhere(): void
    {
        $resultado = $this->filtro->construir([
            'situacao' => 'INATIVO'
        ]);

        $this->assertStringContainsString('WHERE', $resultado['sql']);
    }
}
