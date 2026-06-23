<?php
require_once 'conexao.php';
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$data_inicio = $_GET['data_inicio'] ?? '';
$data_fim = $_GET['data_fim'] ?? '';
$filtro_situacao = $_GET['situacao'] ?? '';

$where = [];
$params = [];

if (!empty($data_inicio)) {
    $where[] = "data_lancamento >= :data_inicio";
    $params[':data_inicio'] = $data_inicio;
}

if (!empty($data_fim)) {
    $where[] = "data_lancamento <= :data_fim";
    $params[':data_fim'] = $data_fim;
}

if (!empty($filtro_situacao)) {
    $where[] = "situacao = :situacao";
    $params[':situacao'] = $filtro_situacao;
}

$sql = "SELECT * FROM lancamento";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY data_lancamento DESC, id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$lancamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filtrosTexto = [];
if (!empty($data_inicio)) {
    $filtrosTexto[] = "Data inicial: " . date('d/m/Y', strtotime($data_inicio));
}
if (!empty($data_fim)) {
    $filtrosTexto[] = "Data final: " . date('d/m/Y', strtotime($data_fim));
}
if (!empty($filtro_situacao)) {
    $filtrosTexto[] = "Situação: " . htmlspecialchars($filtro_situacao);
}

$filtrosLinha = count($filtrosTexto) > 0 ? implode(" | ", $filtrosTexto) : "Sem filtros aplicados.";

$html = '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Lançamentos</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitulo {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }

        .filtros {
            margin-bottom: 15px;
            padding: 10px;
            background: #f2f2f2;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: #fff;
        }

        .receita {
            color: green;
            font-weight: bold;
        }

        .despesa {
            color: red;
            font-weight: bold;
        }

        .ativo {
            color: green;
            font-weight: bold;
        }

        .inativo {
            color: gray;
            font-weight: bold;
        }

        .rodape {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Relatório de Lançamentos</h1>
    <div class="subtitulo">Sistema Financeiro</div>
    <div class="filtros"><strong>Filtros:</strong> ' . $filtrosLinha . '</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Data</th>
                <th>Valor</th>
                <th>Tipo</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>';

if (count($lancamentos) > 0) {
    foreach ($lancamentos as $lancamento) {
        $classeTipo = strtolower($lancamento['tipo_lancamento']);
        $classeSituacao = strtolower($lancamento['situacao']);

        $html .= '
            <tr>
                <td>' . htmlspecialchars($lancamento['id']) . '</td>
                <td>' . htmlspecialchars($lancamento['descricao']) . '</td>
                <td>' . date('d/m/Y', strtotime($lancamento['data_lancamento'])) . '</td>
                <td>R$ ' . number_format($lancamento['valor'], 2, ',', '.') . '</td>
                <td class="' . $classeTipo . '">' . htmlspecialchars($lancamento['tipo_lancamento']) . '</td>
                <td class="' . $classeSituacao . '">' . htmlspecialchars($lancamento['situacao']) . '</td>
            </tr>';
    }
} else {
    $html .= '
        <tr>
            <td colspan="6">Nenhum lançamento encontrado.</td>
        </tr>';
}

$html .= '
        </tbody>
    </table>

    <div class="rodape">
        Gerado em: ' . date('d/m/Y H:i:s') . '
    </div>
</body>
</html>';

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream('relatorio_lancamentos.pdf', ['Attachment' => false]);
exit;
