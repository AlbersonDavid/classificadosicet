<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Consulta SQL para recuperar os produtos mais recentes (por exemplo, os 5 mais recentes)
$sqlRecentProducts = "SELECT * FROM produtos ORDER BY id DESC LIMIT 5";
$stmtRecentProducts = $conn->prepare($sqlRecentProducts);
$stmtRecentProducts->execute();
$recentProducts = $stmtRecentProducts->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
        <div>
            <nav>
                <div class="box">
                    <select>
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
        </div>
        <nav>
            <ul>
                <li><a href="login.php"><img src="assets/login-iconblack.png" alt="Login"> Login</a></li>
            </ul>
        </nav>
    </header>

    <form method="post" action="pesquisa.php" class="search-form">
        <input type="text" name="pesquisa" placeholder="Pesquisar por título ou descrição">
        <input type="submit" value="Pesquisar">
    </form>

    <section id="categorias">
        <br>
        <h2>Categorias</h2><br>
        <div class="category-grid">
            <div class="category">
                <a href="produtos.php?categoria=Livros%20e%20Materiais%20Acadêmicos">Livros e Materiais Acadêmicos</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Moradia%20Estudantil">Moradia Estudantil</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Equipamentos%20e%20Eletrônicos">Equipamentos e Eletrônicos</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Serviços%20e%20Aulas">Serviços e Aulas</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Empregos%20e%20Estágios">Empregos e Estágios</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Eventos%20e%20Grupos%20de%20Estudo">Eventos e Grupos de Estudo</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Móveis%20e%20Itens%20Domésticos">Móveis e Itens Domésticos</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Bicicletas%20e%20Meios%20de%20Transporte%20Alternativos">Bicicletas e Meios de Transporte Alternativos</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Roupas,%20Calçados%20e%20Acessórios">Roupas, Calçados e Acessórios</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Equipamentos%20e%20Instrumentos">Equipamentos e Instrumentos</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Diversos%20e%20Outros%20Itens">Diversos e Outros Itens</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Coisas%20para%20Casa">Coisas para Casa</a>
            </div>
            <div class="category">
                <a href="produtos.php?categoria=Outras%20Coisas">Outras Coisas</a>
            </div>
        </div>
    </section>


    <section id="recent-products">
        <h2>Produtos Recentes</h2>
        <div class="slick-carousel">
            <?php
            foreach ($recentProducts as $product) {
                echo '<div class="carousel-item">';
                echo '<img src="imagens/' . $product['imagem'] . '" alt="' . $product['titulo'] . '">';
                echo '<h3>' . $product['titulo'] . '</h3>';
                echo '<p>' . $product['descricao'] . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </section>


    <div class="container">
        <section id="produtos">
            <br>
            <h2>Produtos</h2><br>
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
                    echo '<img src="imagens/' . $produto['imagem'] . '" alt="Imagem do Produto">';
                    echo '<h3>' . $produto['titulo'] . '</h3>';
                    echo '<p>' . $produto['descricao'] . '</p>';
                    echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
                    echo '<p>Vendedor: ' . $produto['nome_usuario'] . '</p>';

                    // Adicione o botão do WhatsApp com os estilos CSS
                    echo '<a href="https://wa.me/' . $produto['whatsapp_contato'] . '?text=' . urlencode('Gostaria de comprar o produto: ' . $produto['titulo'] . ' - Preço: R$ ' . number_format($produto['preco'], 2, ',', '.')) . '" class="whatsapp-button"><img src="assets/whatsapp-icon.png" alt="WhatsApp"> Comprar via WhatsApp</a>';

                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum produto encontrado.</p>';
            }
            ?>
        </section>
    </div>



    <script type="text/javascript">
        $(document).ready(function() {
            $(".slick-carousel").slick({
                dots: true,
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
            });
        });
    </script>


    <footer>
        <div class="container">
            <p class="footer-text">&copy; Classificados ICET - Projeto SUPER</p>
        </div>
    </footer>
</body>

</html>