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
$sql = "SELECT id, nome, foto_perfil FROM usuarios WHERE matricula = :matricula";
$stmt = $conn->prepare($sql);
$stmt->bindValue(":matricula", $matricula, PDO::PARAM_STR);
if (!$stmt->execute()) {
    echo "Erro na consulta de usuário: " . print_r($stmt->errorInfo(), true);
    exit;
}
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Nome e foto do perfil do usuário
$nomeUsuario = $row["nome"];
$fotoPerfil = $row["foto_perfil"];
$userId = $row["id"];

// Consulta para recuperar os produtos do usuário
$sqlProdutos = "SELECT id, titulo FROM produtos WHERE id_usuario = :id_usuario";
$stmtProdutos = $conn->prepare($sqlProdutos);
$stmtProdutos->bindValue(":id_usuario", $userId, PDO::PARAM_INT);
if (!$stmtProdutos->execute()) {
    echo "Erro na consulta de produtos do usuário: " . print_r($stmtProdutos->errorInfo(), true);
    exit;
}
$resultProdutos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
        <nav>
            <ul>
                <li><a href="categorias.php"><img src="assets/categorias-icon.png" alt="Categorias"> Categorias</a></li>
                <li><a href="produtos.php"><img src="assets/produtos-icon.png" alt="Produtos"> Produtos</a></li>
                <li><a href="logout.php"><img src="assets/logout-icon.png" alt="Logout"> Sair</a></li>
            </ul>
        </nav>
    </header>

    <br><h1>Perfil do Usuário</h1>

    <main>
        <section id="user-profile">
            <h2>Bem-vindo, <?php echo $nomeUsuario; ?></h2>
           <?php if (!empty($fotoPerfil)) { ?>
    <img src="imagens/<?php echo $fotoPerfil; ?>" alt="Foto de Perfil">
<?php } else { ?>
    <p>Você ainda não possui uma foto de perfil. <a href="alterar_foto_perfil.php">Adicionar foto</a></p>
<?php } ?>


            <p>Aqui você pode visualizar e gerenciar suas informações de perfil e produtos cadastrados.</p>
            
            <p>
                <a href="adicionar.php">Adicionar produtos</a>
            </p>

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
        <section id="change-password">
            <h3>Alterar Senha</h3>
            <form action="alterar_senha.php" method="post">
                <div class="form-group">
                    <label>Nova Senha:</label>
                    <input type="password" name="nova_senha" required>
                </div>
                <div class="form-group">
                    <label>Confirme a Nova Senha:</label>
                    <input type="password" name="confirmar_senha" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Alterar Senha">
                </div>
            </form>
        </section>
    </main>

    <footer class="footer-container">
        <p>&copy; Classificados ICET - Projeto SUPER</p>
    </footer>
</body>
</html>
