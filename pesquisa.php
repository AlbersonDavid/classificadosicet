<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesquisa = $_POST['pesquisa'];

    // Consulta o SQL para buscar produtos com base na pesquisa
$sql = "SELECT produtos.*, usuarios.nome AS nome_usuario
        FROM produtos
        INNER JOIN usuarios ON produtos.id_usuario = usuarios.id
        WHERE produtos.titulo LIKE :pesquisa OR produtos.descricao LIKE :pesquisa";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':pesquisa', "%$pesquisa%");
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Resultados da Pesquisa</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
        <nav>
            <ul>
                <li><a href="admin/admin.php"><img src="assets/admin-icon.png" alt="Admin"> Admin</a></li>
                <li><a href="registro.php"><img src="assets/user-icon.png" alt="Criar Usuário"> Criar Usuário</a></li>
                <li><a href="login.php"><img src="assets/login-icon.png" alt="Login"> Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Resultados da Pesquisa</h1>
        <?php if(isset($resultados) && count($resultados) > 0): ?>
            <section id="resultados">
                <h2>Produtos Encontrados:</h2>
                <?php foreach ($resultados as $produto): ?>
                    <div class="produto">
                        <img src="imagens/<?php echo $produto['imagem']; ?>" alt="Imagem do Produto">
                        <h3><?php echo $produto['titulo']; ?></h3>
                        <p><?php echo $produto['descricao']; ?></p>
                        <p>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                        <p>Vendedor: <?php echo $produto['nome_usuario']; ?></p>
                        <p><a href="https://wa.me/<?php echo $produto['whatsapp_contato']; ?>?text=<?php echo urlencode('Gostaria de comprar o produto: ' . $produto['titulo'] . ' - Preço: R$ ' . number_format($produto['preco'], 2, ',', '.')); ?>">Comprar via WhatsApp</a></p>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <p>Nenhum resultado encontrado.</p>
        <?php endif; ?>
    </div>

    <footer>
        <div class="container">
            <p>&copy; Classificados ICET - Projeto SUPER</p>
        </div>
    </footer>
</body>
</html>
