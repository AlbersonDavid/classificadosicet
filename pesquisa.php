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

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00875e">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- CSS  -->
    <link href="min/plugin-min.css" type="text/css" rel="stylesheet">
    <link href="min/custom-min.css" type="text/css" rel="stylesheet">
    <link href="css/pesquisa.css" type="text/css" rel="stylesheet">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Resultado</title>
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
    
    <!--Work-->
    <div class="section scrollspy" id="work">
        <div class="containers form-wrapper">
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
        </div>
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
