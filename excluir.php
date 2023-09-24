<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Verifica se um ID de produto foi especificado na URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Exclue o produto do banco de dados
    $sql = "DELETE FROM produtos WHERE id = $id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

// Redireciona para a página de produtos
header('Location: produtos.php');
exit();
?>
