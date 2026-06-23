<?php
require_once 'conexao.php';
require_once 'enviar_email.php';

$id = $_POST['id'] ?? '';
$descricao = trim($_POST['descricao'] ?? '');
$data_lancamento = $_POST['data_lancamento'] ?? '';
$valor = $_POST['valor'] ?? '';
$tipo_lancamento = trim($_POST['tipo_lancamento'] ?? '');
$situacao = trim($_POST['situacao'] ?? '');

if (!empty($id)) {
    $sql = "UPDATE lancamento
            SET descricao = :descricao,
                data_lancamento = :data_lancamento,
                valor = :valor,
                tipo_lancamento = :tipo_lancamento,
                situacao = :situacao
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':descricao' => $descricao,
        ':data_lancamento' => $data_lancamento,
        ':valor' => $valor,
        ':tipo_lancamento' => $tipo_lancamento,
        ':situacao' => $situacao,
        ':id' => $id
    ]);

    $assunto = "Lançamento alterado no sistema";
    $mensagem = "Um lançamento foi alterado no sistema.\n\n"
              . "ID: {$id}\n"
              . "Descrição: {$descricao}\n"
              . "Data: {$data_lancamento}\n"
              . "Valor: {$valor}\n"
              . "Tipo: {$tipo_lancamento}\n"
              . "Situação: {$situacao}\n";
} else {
    $sql = "INSERT INTO lancamento (descricao, data_lancamento, valor, tipo_lancamento, situacao)
            VALUES (:descricao, :data_lancamento, :valor, :tipo_lancamento, :situacao)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':descricao' => $descricao,
        ':data_lancamento' => $data_lancamento,
        ':valor' => $valor,
        ':tipo_lancamento' => $tipo_lancamento,
        ':situacao' => $situacao
    ]);

    $novoId = $pdo->lastInsertId();

    $assunto = "Novo lançamento incluído no sistema";
    $mensagem = "Um novo lançamento foi cadastrado no sistema.\n\n"
              . "ID: {$novoId}\n"
              . "Descrição: {$descricao}\n"
              . "Data: {$data_lancamento}\n"
              . "Valor: {$valor}\n"
              . "Tipo: {$tipo_lancamento}\n"
              . "Situação: {$situacao}\n";
}

enviarEmailAviso($assunto, $mensagem);

header("Location: index.php");
exit;
