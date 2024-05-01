<?php
session_start();

if (!isset($_SESSION["matricula"])) {

    header("location: login.php");
    exit;
}

include 'includes/conexao.php';

$modo = 'editar';

$id_produto = null;

if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $id_produto, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        // Produto não encontrado, pode exibir uma mensagem de erro ou redirecionar para uma página de erro
        echo "Produto não encontrado.";
        exit();
    }
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $categoria = $_POST['categoria'];
    $imagem = $_FILES['imagem']['name'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $whatsapp_contato = $_POST['whatsapp_contato'];

    // Move a imagem para a pasta de imagens
    move_uploaded_file($_FILES['imagem']['tmp_name'], 'imagens/' . $imagem);

    // Atualiza os dados no banco de dados
    $sql = "UPDATE produtos SET categoria = ?, imagem = ?, titulo = ?, descricao = ?, preco = ?, whatsapp_contato = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $categoria, PDO::PARAM_STR);
    $stmt->bindParam(2, $imagem, PDO::PARAM_STR);
    $stmt->bindParam(3, $titulo, PDO::PARAM_STR);
    $stmt->bindParam(4, $descricao, PDO::PARAM_STR);
    $stmt->bindParam(5, $preco, PDO::PARAM_STR);
    $stmt->bindParam(6, $whatsapp_contato, PDO::PARAM_STR);
    $stmt->bindParam(7, $id_produto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redireciona para a página de produtos após a edição
        header('Location: perfil.php');
        exit();
    } else {
        // Tratamento de erro, se necessário
        echo "Erro ao atualizar o produto no banco de dados.";
    }
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
    <link href="css/login.css" type="text/css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Editar Produto</title>
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
                        <li><a href="perfil.php">Perfil</a></li>
                    </ul>
                    <ul id="nav-mobile" class="side-nav">
                        <li><a href="perfil.php">Perfil</a></li>
                    </ul>
                    <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
                </div>
            </div>
        </nav>
    </div>

    <!--Work-->
    <div class="section scrollspy" id="work">
        <div class="containers form-wrapper">
            <br>
            <h2>Editar Produto</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria">
       <option value="Livros e Materiais Acadêmicos">Livros e Materiais Acadêmicos</option>
            <option value="Moradia Estudantil">Moradia Estudantil</option>
            <option value="Equipamentos e Eletrônicos">Equipamentos e Eletrônicos</option>
            <option value="Serviços e Aulas">Serviços e Aulas</option>
            <option value="Empregos e Estágios">Empregos e Estágios</option>
            <option value="Eventos e Grupos de Estudo">Eventos e Grupos de Estudo</option>
            <option value="Móveis e Itens Domésticos">Móveis e Itens Domésticos</option>
            <option value="Bicicletas e Meios de Transporte Alternativos">Bicicletas e Meios de Transporte Alternativos</option>
            <option value="Roupas, Calçados e Acessórios">Roupas, Calçados e Acessórios</option>
            <option value="Equipamentos e Instrumentos">Equipamentos e Instrumentos</option>
            <option value="Diversos e Outros Itens">Diversos e Outros Itens</option>
            <option value="Coisas para Casa">Coisas para Casa</option>
        </select><br>
        <label for="imagem">Imagem:</label>
        <input type="file" id="imagem" name="imagem"><br>
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo $produto['titulo']; ?>"><br>
        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" value="<?php echo $produto['descricao']; ?>"><br>
        <label for="preco">Preço:</label>
        <input type="text" id="preco" name="preco" value="<?php echo $produto['preco']; ?>"><br>
        <label for="whatsapp_contato">Número de WhatsApp:</label>
        <input type="text" id="whatsapp_contato" name="whatsapp_contato" value="<?php echo $produto['whatsapp_contato']; ?>"><br>
        <input type="submit" class="btn-primary" value="Salvar Alterações">
    </form>
        </div>
    </div>

    <!--Footer-->
    <footer style="position: absolute; width: 100%;" id="contact" class="page-footer default_color scrollspy">
        <div class="col s12">
            <h5 class="center header text_h2" style="color: white;"> Classificados ICET </h5>
        </div>
    </footer>


    <!--  Scripts-->
    <script>
        M.AutoInit();
    </script>
    <script src="min/plugin-min.js"></script>
    <script src="min/custom-min.js"></script>


</body>

</html>