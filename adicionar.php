<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $categoria = $_POST['categoria'];
    $imagem = $_FILES['imagem']['name'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $whatsapp_contato = $_POST['whatsapp_contato'];

    // Adicione a recuperação do campo "tipo"
    $tipo = $_POST['tipo'];

    // Move a imagem para a pasta de imagens
    move_uploaded_file($_FILES['imagem']['tmp_name'], 'imagens/' . $imagem);

    // Recupera o ID do usuário da sessão
    session_start();
    $id_usuario = $_SESSION['id'];

    // Insere os dados no banco de dados
    $sql = "INSERT INTO produtos (categoria, imagem, titulo, descricao, preco, whatsapp_contato, id_usuario, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $categoria, PDO::PARAM_STR);
    $stmt->bindParam(2, $imagem, PDO::PARAM_STR);
    $stmt->bindParam(3, $titulo, PDO::PARAM_STR);
    $stmt->bindParam(4, $descricao, PDO::PARAM_STR);
    $stmt->bindParam(5, $preco, PDO::PARAM_STR);
    $stmt->bindParam(6, $whatsapp_contato, PDO::PARAM_STR);
    $stmt->bindParam(7, $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(8, $tipo, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Redireciona para a página de produtos
        header('Location: perfil.php');
        exit();
    } else {
        // Tratamento de erro, se necessário
        echo "Erro ao inserir o produto no banco de dados.";
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
    <link href="css/adicionar.css" type="text/css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Adicionar Produto</title>
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
            <h1>Adicionar Produto</h1>
            <form method="post" enctype="multipart/form-data">
                <div class="input-field col s12">
                    <select id="categoria" name="categoria">
                        <option value="" disabled selected>Escolha a Categoria:</option>
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
                    </select>
                    <label for="categoria">Categoria:</label>
                </div>
                <!-- Adicione o campo "Tipo" -->
                <div class="input-field col s12">
                    <select id="tipo" name="tipo">
                        <option value="" disabled selected>Escolha o Tipo:</option>
                        <option value="venda">Venda</option>
                        <option value="aluguel">Aluguel</option>
                    </select>
                    <label for="tipo">Tipo:</label>
                </div>

                <label for="imagem">Imagem:</label>
                <input type="file" id="imagem" name="imagem"><br><br>
                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo"><br>
                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao"><br>
                <label for="preco">Preço:</label>
                <input type="text" id="preco" name="preco"><br>
                <label for="whatsapp_contato">Número de WhatsApp:</label>
                <input type="text" id="whatsapp_contato" name="whatsapp_contato"><br>
                <input type="submit" class="btn-primary" value="Adicionar">
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