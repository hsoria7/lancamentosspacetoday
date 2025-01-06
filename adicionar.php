<?php
// Inclui a conexão
require_once 'conexao.php';

// Captura os dados enviados pelo formulário
$nome_live = $_POST['nome_live'] ?? null;
$foguete = $_POST['foguete'] ?? null;
$data = $_POST['data'] ?? null;
$partiu = $_POST['partiu'] ?? null; // Sim ou Não
$scrub = $_POST['scrub'] ?? null;  // Sim ou Não
$explodiu = $_POST['explodiu'] ?? null; // Sim ou Não

// Verifica se todos os campos obrigatórios foram preenchidos
if ($nome_live && $foguete && $data && $partiu !== null && $scrub !== null && $explodiu !== null) {
    // Conecta ao banco e insere os dados
    $connection = getConexao();

    // Prepara a consulta SQL
    $stmt = $connection->prepare("INSERT INTO lancamentos (Nome_live, foguete, data, partiu, scrub, explodiu) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Erro na preparação da consulta: ' . $connection->error);
    }

    // Altera os tipos para "sss" (string) porque `Sim` e `Não` são strings
    $stmt->bind_param("ssssss", $nome_live, $foguete, $data, $partiu, $scrub, $explodiu);

    // Executa a consulta
    if ($stmt->execute()) {
        header('Location: index2.php'); // Redireciona para a página inicial
        exit;
    } else {
        echo "Erro ao adicionar dados: " . $stmt->error;
    }

    // Fecha a consulta e a conexão
    $stmt->close();
    $connection->close();
} else {
    echo "Por favor, preencha todos os campos obrigatórios!";
}
?>


