<!DOCTYPE html>
<html>
<head>
    <title>Pesquisa de Produtos</title>
    <link rel="stylesheet" type="text/css" href="css/pesquisa.css">
</head>
<body>
    <h1>Pesquisa de Produtos</h1>
    <?php
    
    include 'includes/conexao.php';
    
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pesquisa = $_POST['pesquisa'];

        // Consulta o SQL para buscar produtos com base na pesquisa
        $sql = "SELECT * FROM produtos WHERE titulo LIKE :pesquisa OR descricao LIKE :pesquisa";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':pesquisa', "%$pesquisa%");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Exibe os resultados da pesquisa
            echo '<h2>Resultados da Pesquisa:</h2>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Imagem</th>';
            echo '<th>Categoria</th>';
            echo '<th>Titulo</th>';
            echo '<th>Descrição</th>';
            echo '<th>Preço</th>';
            echo '<th>Contato</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($produto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td><img src="imagens/' . $produto['imagem'] . '" alt="Imagem do Produto"></td>';
                echo '<td>' . $produto['categoria'] . '</td>';
                echo '<td>' . $produto['titulo'] . '</td>';
                echo '<td>' . $produto['descricao'] . '</td>';
                echo '<td>R$ ' . number_format($produto['preco'], 2, ',', '.') . '</td>';
                echo '<td><a href="https://wa.me/' . $produto['whatsapp_contato'] . '?text=' . urlencode('Gostaria de comprar o produto: ' . $produto['titulo'] . ' - Preço: R$ ' . number_format($produto['preco'], 2, ',', '.')) . '">Comprar via WhatsApp</a></td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>Nenhum resultado encontrado.</p>';
        }
    }
    ?>
    <h2>Faça uma Pesquisa</h2>
    <form method="post">
        <input type="text" name="pesquisa" placeholder="Pesquisar por título ou descrição">
        <input type="submit" value="Pesquisar">
    </form>
    <p>
        <a href="produtos.php">Voltar para a lista de produtos</a>
    </p>
    
     <footer class="footer-container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>