<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Consulta SQL para recuperar os produtos mais recentes (por exemplo, os 5 mais recentes)
$sqlRecentProducts = "SELECT * FROM produtos ORDER BY id DESC LIMIT 3";
$stmtRecentProducts = $conn->prepare($sqlRecentProducts);
$stmtRecentProducts->execute();
$recentProducts = $stmtRecentProducts->fetchAll(PDO::FETCH_ASSOC);

?>

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
    <link href="css/styles.css" type="text/css" rel="stylesheet">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Classificados ICET</title>
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
                        <li><a href="login.php">Login</a></li>
                    </ul>
                    <ul id="nav-mobile" class="side-nav">
                        <li><a href="login.php">Login</a></li>
                    </ul>
                    <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
                </div>
            </div>
        </nav>
    </div>

    <!--Hero-->
    <div class="section no-pad-bot" id="index-banner">
        <div class="container">
            <h1 class="text_h center header cd-headline letters type">
                <span>Site</span>
                <span class="cd-words-wrapper waiting">
                    <b class="is-visible">de Compras</b>
                    <b>de Vendas</b>
                    <b>Universitario</b>
                </span>
            </h1>
        </div>
    </div>

    <!--Intro and service-->
    <div id="intro" class="section scrollspy">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <h2 class="center header text_h2"> Produtos Aunciados Recentemente </h2>
                </div>

                <!--
                <div>
                    <?php
                    if (count($recentProducts) > 0) {
                        foreach ($recentProducts as $product) {
                        echo '<div class="center promo promo-example">';
                        echo '<img style="max-width: 200px;
                        max-height: 150px;
                        width: auto;
                        height: auto;" src="imagens/' . $product['imagem'] . '" alt="' . $product['titulo'] . '">';
                        echo '<h5 class="promo-caption>' . $product['titulo'] . '</h5>';
                        echo '<p class="light center>' . ucfirst($product['titulo']) . '</p>';
                        echo '</div>';
                        }
                    } else {
                        echo '<p>Nenhum produto encontrado.</p>';
                    }
                    ?>
                </div>
                
                <div class="col s12 m4 l4">
                <div class="center promo promo-example">
                    <i class="mdi-hardware-desktop-windows"></i>
                    <h5 class="promo-caption">Fully responsive</h5>
                    <p class="light center">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
                </div>
            </div>-->
            </div>
        </div>
    </div>

    <!--Work-->
    <div class="section scrollspy" id="work">
        <div class="container">
            <h2 class="header text_b">Produtos </h2>
            <div class="row">
                            <?php
                            // Verifica se uma categoria foi selecionada
                            $categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;

                            // Consulta o SQL para recuperar produtos por categoria ou todos os produtos
                            $sqlProdutos = "SELECT produtos.*, usuarios.nome AS nome_usuario, usuarios.foto_perfil FROM produtos INNER JOIN usuarios ON produtos.id_usuario = usuarios.id";
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
                                    echo '<div class="col s12 m4 l4">';
                                    echo '<div class="card">';
                                    echo '<div class="card-image waves-effect waves-block waves-light">';
                                    echo '<img class="activator" style="width: 420px; height: 350px;" src="imagens/' . $produto['imagem'] . '" alt="Imagem do Produto">';
                                    echo '</div>';
                                    echo '<div class="card-content">';
                                    echo '<span class="card-title activator grey-text text-darken-4" style="font-size: 12px;">' . $produto['titulo'] . '<i class="mdi-navigation-more-vert right"></i></span>';
                                    echo '</div>';
                                    echo '<div class="card-reveal">';
                                    echo '<span class="card-title grey-text text-darken-4">' . $produto['titulo'] . '<i class="mdi-navigation-close right"></i></span>';
                                    echo '<p>' . $produto['descricao'] . '</p>';
                                    echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
                                    echo '<p><strong>Vendedor:</strong> ' . $produto['nome_usuario'] . '</p>';
                                    echo '<p>Tipo: ' . ucfirst($produto['tipo']) . '</p>';
                                    echo '<a href="https://wa.me/' . $produto['whatsapp_contato'] . '?text=' . urlencode('Gostaria do Item: ' . $produto['titulo'] . ' - Preço: R$ ' . number_format($produto['preco'], 2, ',', '.')) . '" class="whatsapp-button" target="_blank"><img src="assets/whatsapp-icon.png" alt="WhatsApp"> Contatar via WhatsApp</a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>Nenhum produto encontrado.</p>';
                            }

                            ?>
                <!--<div class="col s12 m4 l4">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator" src="img/project2.jpeg">
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">Project Title <i class="mdi-navigation-more-vert right"></i></span>
                        <p><a href="#">Project link</a></p>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Project Title <i class="mdi-navigation-close right"></i></span>
                        <p>Here is some more information about this project that is only revealed once clicked on.</p>
                    </div>
                </div>
            </div>-->
            </div>
        </div>
    </div>

    <!--Parallax-->
    <div class="parallax-container">
        <div class="parallax"><img src="assets/bannericet.png"></div>
    </div>

    <!--Footer-->
    <footer id="contact" class="page-footer default_color scrollspy">
        <div class="col s12">
            <h4 class="center header text_h2" style="color: white;"> Fale Conosco </h4>
        </div>
        <div class="container">
            <div class="center">
                <div class="col l6 s12">
                    <form class="col s12" action="contact.php" method="post">
                        <div class="row">
                            <div class="input-field col s6">
                                <i class="mdi-action-account-circle prefix white-text"></i>
                                <input id="icon_prefix" name="name" type="text" class="validate white-text">
                                <label for="icon_prefix" class="white-text">Seu Nome</label>
                            </div>
                            <div class="input-field col s6">
                                <i class="mdi-communication-email prefix white-text"></i>
                                <input id="icon_email" name="email" type="email" class="validate white-text">
                                <label for="icon_email" class="white-text">Seu E-mail</label>
                            </div>
                            <div class="input-field col s12">
                                <i class="mdi-editor-mode-edit prefix white-text"></i>
                                <textarea id="icon_prefix2" name="message" class="materialize-textarea white-text"></textarea>
                                <label for="icon_prefix2" class="white-text">Sua Mensagem</label>
                            </div>
                            <div class="col offset-s7 s5">
                                <button class="btn waves-effect waves-light red darken-1" type="submit">Enviar
                                    <i class="mdi-content-send right white-text"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </footer>


    <!--  Scripts-->
    <script src="min/plugin-min.js"></script>
    <script src="min/custom-min.js"></script>

</body>