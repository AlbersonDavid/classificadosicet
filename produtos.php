<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Produtos</title>
    <link rel="stylesheet" href="css/produtos.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logo.png" alt="Logo da Página"></a>
        </div>
    </header>
    
    <div class="product-container">
        <h1>Painel de Produtos</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Categoria</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Contato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Arquivo de conexão com o banco de dados
                    include 'includes/conexao.php';

                    // Verifica se uma categoria foi especificada na URL
                    $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

                    // Construae a consulta SQL com base na categoria (se especificada)
                    $sql = "SELECT * FROM produtos";
                    if ($categoria) {
                        $sql .= " WHERE categoria = '$categoria'";
                    }

                    // Executa a consulta SQL
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    // Exibe os produtos
                    while ($produto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td class="product"><img src="imagens/' . $produto['imagem'] . '" alt="Imagem do Produto"></td>';
                        echo '<td>' . $produto['categoria'] . '</td>';
                        echo '<td>' . $produto['titulo'] . '</td>';
                        echo '<td>' . $produto['descricao'] . '</td>';
                        echo '<td>R$ ' . number_format($produto['preco'], 2, ',', '.') . '</td>';
                        echo '<td><a class="buy-button" href="https://wa.me/' . $produto['whatsapp_contato'] . '?text=' . urlencode('Gostaria de comprar o produto: ' . $produto['titulo'] . ' - Preço: R$ ' . number_format($produto['preco'], 2, ',', '.')) . '" target="_blank">Comprar via WhatsApp</a></td>';
echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
     <br>
    <br>
    <p>
        <a href="categorias.php">Voltar para categorias de produtos</a>
        <a href="perfil.php">Voltar para perfil</a>
    </p><br>
    </div>
    
    <footer class="footer-container">
        <p>&copy; Classificados ICET - Projeto SUPER</p>
    </footer>
</body>
</html>
