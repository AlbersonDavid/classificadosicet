<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
 <script type="text/javascript" src="js/main.js"></script>

    <link rel="stylesheet" href="css/produtos.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
        <nav>
            <div class="box">
                <select id="categoryDropdown" onchange="redirectToCategory()">
                    <option value="all">Todas as Categorias</option>
                    <option>Categorias</option>
                    <option>Livros e Materiais Acadêmicos</option>
                    <option>Moradia Estudantil</option>
                    <option>Equipamentos e Eletrônicos</option>
                    <option>Serviços e Aulas</option>
                    <option>Empregos e Estágios</option>
                    <option>Eventos e Grupos de Estudo</option>
                    <option>Móveis e Itens Domésticos</option>
                    <option>Bicicletas e Meios de Transporte Alternativos</option>
                    <option>Roupas, Calçados e Acessórios</option>
                    <option>Equipamentos e Instrumentos</option>
                    <option>Diversos e Outros Itens</option>
                    <option>Coisas para Casa</option>
                    <option>Outras Coisas</option>
                </select>
            </div>
        </nav>
    </header>

    <div class="main-content">
        <div class="search-container">
            <form method="post" action="pesquisa.php" class="search-form">
                <input type="text" name="pesquisa" placeholder="Pesquisar por título ou descrição">
                <input type="submit" value="Pesquisar">
            </form>
        </div>

        <div class="product-container">
            <h1>Produtos</h1>
            <div class="grid-container">
                <?php
                // Arquivo de conexão com o banco de dados
                include 'includes/conexao.php';

                // Verifica se uma categoria foi especificada na URL
                $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

                // Construa a consulta SQL com base na categoria (se especificada)
                $sql = "SELECT * FROM produtos";
                if ($categoria) {
                    $sql .= " WHERE categoria = '$categoria'";
                }

                // Executa a consulta SQL
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                // Exibe os produtos
                while ($produto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="product">';
                    echo '<img src="imagens/' . $produto['imagem'] . '" alt="Imagem do Produto">';
                    echo '<p>Categoria: ' . $produto['categoria'] . '</p>';
                    echo '<h2>' . $produto['titulo'] . '</h2>';
                    echo '<p>' . $produto['descricao'] . '</p>';
                    echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
                    echo '<a class="buy-button" href="https://wa.me/' . $produto['whatsapp_contato'] . '?text=' . urlencode('Gostaria de comprar o produto: ' . $produto['titulo'] . ' - Preço: R$ ' . number_format($produto['preco'], 2, ',', '.')) . '" target="_blank">Comprar via WhatsApp</a>';
                    echo '</div>';
                }
                ?>
            </div>
            <br>
            <br>
            <p>
                <a href="categorias.php">Voltar para categorias de produtos</a>
                <a href="perfil.php">Voltar para perfil</a>
            </p><br>
        </div>

        <div class="spacer"></div>
    </div>
    <footer>
        <div class="container">
            <p class="footer-text">&copy; Classificados ICET - Projeto SUPER</p>
        </div>
    </footer>
</body>

</html>
