<?php
require_once 'conexao.php';

$acao = $_GET['acao'] ?? '';
$id = $_GET['id'] ?? '';

$data_inicio = $_GET['data_inicio'] ?? '';
$data_fim = $_GET['data_fim'] ?? '';
$filtro_situacao = $_GET['situacao'] ?? '';

$registro = [
    'id' => '',
    'descricao' => '',
    'data_lancamento' => '',
    'valor' => '',
    'tipo_lancamento' => '',
    'situacao' => ''
];

if ($acao === 'editar' && !empty($id)) {
    $sql = "SELECT * FROM lancamento WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dados) {
        $registro = $dados;
    }
}

if ($acao === 'excluir' && !empty($id)) {
    $sql = "DELETE FROM lancamento WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    header("Location: index.php");
    exit;
}

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

$sqlLancamentos = "SELECT * FROM lancamento";
if (!empty($where)) {
    $sqlLancamentos .= " WHERE " . implode(" AND ", $where);
}
$sqlLancamentos .= " ORDER BY data_lancamento DESC, id DESC";

$stmt = $pdo->prepare($sqlLancamentos);
$stmt->execute($params);
$lancamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$queryString = http_build_query([
    'data_inicio' => $data_inicio,
    'data_fim' => $data_fim,
    'situacao' => $filtro_situacao
]);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Lançamentos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        h1, h2 {
            margin-top: 0;
            color: #333;
        }

        p {
            color: #666;
        }

        form.grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button, .btn {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-salvar {
            background: #28a745;
            color: #fff;
        }

        .btn-limpar {
            background: #6c757d;
            color: #fff;
        }

        .btn-editar {
            background: #ffc107;
            color: #000;
        }

        .btn-excluir {
            background: #dc3545;
            color: #fff;
        }

        .btn-filtrar {
            background: #007bff;
            color: #fff;
        }

        .btn-pdf {
            background: #b30000;
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background: #007bff;
            color: #fff;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .acoes a {
            margin: 0 4px;
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

        .linha-botoes {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 900px) {
            form.grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="card">
        <h1>CRUD do Natan Lançamentos</h1>
        <p>Cadastro, edição, exclusão, listagem e filtro de lançamentos.</p>
    </div>

    <div class="card">
        <h2><?= $acao === 'editar' ? 'Editar Lançamento' : 'Cadastrar Lançamento' ?></h2>

        <form class="grid" action="salvar.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($registro['id']) ?>">

            <div class="full">
                <label>Descrição</label>
                <input type="text" name="descricao" required value="<?= htmlspecialchars($registro['descricao']) ?>">
            </div>

            <div>
                <label>Data do Lançamento</label>
                <input type="date" name="data_lancamento" required value="<?= htmlspecialchars($registro['data_lancamento']) ?>">
            </div>

            <div>
                <label>Valor</label>
                <input type="number" step="0.01" name="valor" required value="<?= htmlspecialchars($registro['valor']) ?>">
            </div>

            <div>
                <label>Tipo de Lançamento</label>
                <select name="tipo_lancamento" required>
                    <option value="">Selecione</option>
                    <option value="RECEITA" <?= $registro['tipo_lancamento'] === 'RECEITA' ? 'selected' : '' ?>>RECEITA</option>
                    <option value="DESPESA" <?= $registro['tipo_lancamento'] === 'DESPESA' ? 'selected' : '' ?>>DESPESA</option>
                </select>
            </div>

            <div>
                <label>Situação</label>
                <select name="situacao" required>
                    <option value="">Selecione</option>
                    <option value="ATIVO" <?= $registro['situacao'] === 'ATIVO' ? 'selected' : '' ?>>ATIVO</option>
                    <option value="INATIVO" <?= $registro['situacao'] === 'INATIVO' ? 'selected' : '' ?>>INATIVO</option>
                </select>
            </div>

            <div class="full linha-botoes">
                <button type="submit" class="btn btn-salvar">
                    <?= $acao === 'editar' ? 'Atualizar Lançamento' : 'Cadastrar Lançamento' ?>
                </button>
                <a href="index.php" class="btn btn-limpar">Limpar</a>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Filtros de Lançamentos</h2>

        <form class="grid" method="GET" action="index.php">
            <div>
                <label>Data Inicial</label>
                <input type="date" name="data_inicio" value="<?= htmlspecialchars($data_inicio) ?>">
            </div>

            <div>
                <label>Data Final</label>
                <input type="date" name="data_fim" value="<?= htmlspecialchars($data_fim) ?>">
            </div>

            <div>
                <label>Situação</label>
                <select name="situacao">
                    <option value="">Todas</option>
                    <option value="ATIVO" <?= $filtro_situacao === 'ATIVO' ? 'selected' : '' ?>>ATIVO</option>
                    <option value="INATIVO" <?= $filtro_situacao === 'INATIVO' ? 'selected' : '' ?>>INATIVO</option>
                </select>
            </div>

            <div class="full linha-botoes">
                <button type="submit" class="btn btn-filtrar">Filtrar</button>
                <a href="index.php" class="btn btn-limpar">Limpar Filtros</a>
                <a href="gerar_pdf.php?<?= $queryString ?>" target="_blank" class="btn btn-pdf">Gerar PDF</a>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Listagem de Lançamentos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Data</th>
                    <th>Valor</th>
                    <th>Tipo</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($lancamentos) > 0): ?>
                <?php foreach ($lancamentos as $lancamento): ?>
                    <tr>
                        <td><?= htmlspecialchars($lancamento['id']) ?></td>
                        <td><?= htmlspecialchars($lancamento['descricao']) ?></td>
                        <td><?= date('d/m/Y', strtotime($lancamento['data_lancamento'])) ?></td>
                        <td>R$ <?= number_format($lancamento['valor'], 2, ',', '.') ?></td>
                        <td class="<?= strtolower($lancamento['tipo_lancamento']) ?>">
                            <?= htmlspecialchars($lancamento['tipo_lancamento']) ?>
                        </td>
                        <td class="<?= strtolower($lancamento['situacao']) ?>">
                            <?= htmlspecialchars($lancamento['situacao']) ?>
                        </td>
                        <td class="acoes">
                            <a class="btn btn-editar" href="index.php?acao=editar&id=<?= $lancamento['id'] ?>">Editar</a>
                            <a class="btn btn-excluir" href="index.php?acao=excluir&id=<?= $lancamento['id'] ?>" onclick="return confirm('Deseja realmente excluir este lançamento?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Nenhum lançamento encontrado.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
