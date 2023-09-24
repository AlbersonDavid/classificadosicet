<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Recupera todas as categorias do banco de dados
$sqlCategorias = "SELECT * FROM categorias";
$stmtCategorias = $conn->prepare($sqlCategorias);
$stmtCategorias->execute();
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classificados ICET</title>
    <link rel="stylesheet" href="./css/admin.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logo.png" alt="Logo da Página"></a>
        </div>
        <nav>
            <ul>
                <li><a href="admin/admin.php"><img src="assets/admin-icon.png" alt="Admin"> Admin</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="banner">
        <img src="assets/banner-image.jpg" alt="Banner">
    </div>

    <div class="container">
        <section id="categorias">
            <h2>Categorias</h2>
            <ul>
                <?php foreach ($categorias as $categoria) : ?>
                    <li><a href="index.php?categoria_id=<?php echo $categoria['id']; ?>"><?php echo $categoria['nome']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section id="produtos">
            <h2>Produtos</h2>

            <?php
            // Verifica se uma categoria foi selecionada
            $categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;

            // Consulta o SQL para recuperar produtos por categoria ou todos os produtos
            $sqlProdutos = "SELECT produtos.*, usuarios.nome AS nome_usuario
                            FROM produtos
                            INNER JOIN usuarios ON produtos.id_usuario = usuarios.id";
            
            if ($categoria_id) {
                $sqlProdutos .= " WHERE produtos.categoria = :categoria";
            }

            $stmtProdutos = $conn->prepare($sqlProdutos);
            
            if ($categoria_id) {
                $stmtProdutos->bindParam(':categoria', $categorias[$categoria_id - 1]['nome'], PDO::PARAM_STR);
            }

            $stmtProdutos->execute();
            $produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

            if (count($produtos) > 0) {
                foreach ($produtos as $produto) {
                    echo '<div class="produto">';
                    // Adicione a imagem do produto
                    echo '<img src="imagens/' . $produto['imagem'] . '" alt="Imagem do Produto">';
                    echo '<h3>' . $produto['titulo'] . '</h3>';
                    echo '<p>' . $produto['descricao'] . '</p>';
                    echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
                    echo '<p>Vendedor: ' . $produto['nome_usuario'] . '</p>';
                    echo '<td><a href="https://wa.me/' . $produto['whatsapp_contato'] . '?text=' . urlencode('Gostaria de comprar o produto: ' . $produto['titulo'] . ' - Preço: R$ ' . number_format($produto['preco'], 2, ',', '.')) . '">Comprar via WhatsApp</a></td>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum produto encontrado.</p>';
            }
            ?>
        </section>
    </div>

    <footer>
        <div class="container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
        </div>
    </footer>
</body>
</html>