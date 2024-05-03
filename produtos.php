<!DOCTYPE html>
<html lang="pt-BR">


<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00875e">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- CSS  -->
    <link href="min/plugin-min.css" type="text/css" rel="stylesheet">
    <link href="min/custom-min.css" type="text/css" rel="stylesheet">
    <link href="css/produtos.css" type="text/css" rel="stylesheet">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Produtos</title>
</head>

<body id="top" class="scrollspy">


    <!-- Pre Loader -->
    <div id="loader-wrapper">
        <div id="loader"></div>

        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>

    </div>

    <!--Navigation-->
    <div class="navbar-fixed">
        <nav id="nav_f" class="default_color" role="navigation">
            <div class="container">
                <div class="nav-wrapper">
                    <a href="index.php" id="logo-container" class="brand-logo">
                        <img class="logo" src="assets/logoclassificados.png">
                    </a>
                    <ul class="right hide-on-med-and-down">
                    <li><a href="index.php">Inicial</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    
                    </ul>
                    <ul id="nav-mobile" class="side-nav">
                    <li><a href="index.php">Inicial</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                
                    </ul>
                    <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
                </div>
            </div>
        </nav>
    </div>
        <nav>
            <div class="catbox">
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
        </div>

        <div class="spacer"></div>
    </div>
    
    <!--Footer-->
    <footer style="position: absolute; width: 100%;" id="contact" class="page-footer default_color scrollspy">
        <div class="col s12">
            <h5 class="center header text_h2" style="color: white;"> Classificados ICET </h5>
        </div>
    </footer>


    <!--  Scripts-->
    <script src="min/plugin-min.js"></script>
    <script src="min/custom-min.js"></script>

</body>

</html>