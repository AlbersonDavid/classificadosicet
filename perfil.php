<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["matricula"])) {
    // Redireciona para a página de login se o usuário não estiver logado
    header("location: login.php");
    exit;
}

// Dados do usuário
$matricula = $_SESSION["matricula"];

// Consulta para recuperar o nome do usuário
$sql = "SELECT nome FROM usuarios WHERE matricula = :matricula";
$stmt = $conn->prepare($sql);
$stmt->bindValue(":matricula", $matricula, PDO::PARAM_STR); // Use PDO::PARAM_STR para strings
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Nome do usuário
$nomeUsuario = $row["nome"];

// Consulta para recuperar os produtos do usuário
$sqlProdutos = "SELECT id, titulo FROM produtos WHERE id_usuario = :matricula";
$stmtProdutos = $conn->prepare($sqlProdutos);
$stmtProdutos->bindValue(":matricula", $matricula, PDO::PARAM_STR);
$stmtProdutos->execute();
$resultProdutos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="css/wecolme.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin/admin.php">Admin</a></li>
                <li><a href="categorias.php">Categorias</a></li>
                <li><a href="pesquisa.php">Pesquisar Produtos</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                <li><a href="adicionar.php">Adicionar Produto</a></li>
                <li><a href="perfil.php">Perfil do Usuário</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
        <h1>Perfil do Usuário</h1>
    </header>

    <main>
        <section id="user-profile">
            <h2>Bem-vindo, <?php echo $nomeUsuario; ?></h2>
            <p>Aqui você pode visualizar e gerenciar suas informações de perfil e produtos cadastrados.</p>

            <h3>Seus Produtos:</h3>
            <ul>
                <?php foreach ($resultProdutos as $produto) { ?>
                    <li>
                        <?php echo $produto["titulo"]; ?> -
                        <a href="editar.php?id=<?php echo $produto["id"]; ?>">Editar</a> |
                        <a href="excluir.php?id=<?php echo $produto["id"]; ?>">Excluir</a>
                    </li>
                <?php } ?>
            </ul>
        </section>
    </main>

     <footer class="footer-container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>