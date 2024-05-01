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
$sqlProdutos = "SELECT id, titulo, imagem FROM produtos WHERE id_usuario = :id_usuario";
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

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00875e">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- CSS  -->
    <link href="min/plugin-min.css" type="text/css" rel="stylesheet">
    <link href="min/custom-min.css" type="text/css" rel="stylesheet">
    <link href="css/perfil.css" type="text/css" rel="stylesheet">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Perfil</title>
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
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                    <ul id="nav-mobile" class="side-nav">
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                    <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
                </div>
            </div>
        </nav>
    </div>

    <div id="intro" class="section scrollspy">
        <div class="container">
            <div class="row">
            <div style="float: right;" class="col s12" id="user-profile">
                    <?php if (!empty($fotoPerfil)) { ?>
                        <img src="imagens/<?php echo $fotoPerfil; ?>" alt="Foto de Perfil">
                    <?php } else { ?>
                        <p>Você ainda não possui uma foto de perfil. <a href="alterar_foto_perfil.php">Adicionar foto</a></p>
                    <?php } ?>
                </div>
                <div class="col s12">
                    <h2 class="center header text_h2"> Perfil do Usuário <br> Bem-vindo, <?php echo $nomeUsuario; ?> </h2>
                </div>
                <div class="col s12">
                    <div class="center promo promo-example">
                        <h5 class="promo-caption">Aqui você pode visualizar e gerenciar suas informações de perfil e produtos cadastrados.</h5>
                        <p class="light center">
                            <a href="adicionar.php">Adicionar produtos</a>
                        </p>
                    </div>
                </div>
                <div class="col s12">
                    <h2 class="center header text_h2"> Seus Produtos </h2>
                </div>
                <?php foreach ($resultProdutos as $produto) {
                    echo '<div class="col s12 m4 l4">';
                    echo '<div class="card">';
                    echo '<div class="card-image waves-effect waves-block waves-light">';
                    echo '<img class="activator" style="width: 420px; height: 350px;" src="imagens/' . $produto['imagem'] . '" alt="Imagem do Produto">';
                    echo '</div>';
                    echo '<div class="card-content">';
                    echo '</div>';
                    echo '<div class="card-reveal">';
                    echo '<span class="card-title grey-text text-darken-4">' . $produto['titulo'] . '<i class="mdi-navigation-close right"></i></span>';
                    echo '<a href="editar.php?id='. $produto['id'] .'">Editar</a>';
                    echo '<a href="excluir.php?id='. $produto['id'] .'">Excluir</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                } ?>
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