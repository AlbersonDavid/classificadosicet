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
        header('Location: produtos.php');
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="css/adicionar.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logo.png" alt="Logo da Página"></a>
        </div>
    </header>

    <h1>Editar Produto</h1>
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
        <input type="submit" value="Salvar Alterações">
    </form>
    <p>
        <a href="produtos.php">Voltar para a lista de produtos</a>
    </p><br>

    <footer class="footer-container">
        <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>
