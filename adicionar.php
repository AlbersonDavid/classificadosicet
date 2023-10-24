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
        header('Location: produtos.php');
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="css/adicionar.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logo.png" alt="Logo da Página"></a>
        </div>
        <nav>
            <ul>
                <li><a href="admin/admin.php"><img src="assets/admin-icon.png" alt="Admin"> Admin</a></li>
                <li><a href="registro.php"><img src="assets/user-icon.png" alt="Criar Usuário"> Criar Usuário</a></li>
                <li><a href="login.php"><img src="assets/login-icon.png" alt="Login"> Login</a></li>
            </ul>
        </nav>
    </header>
    
    
    <br><h1>Adicionar Produto</h1>
    <form method="post" enctype="multipart/form-data">
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
        
        <!-- Adicione o campo "Tipo" -->
        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo">
            <option value="venda">Venda</option>
            <option value="aluguel">Aluguel</option>
        </select><br>

        <label for="imagem">Imagem:</label>
        <input type="file" id="imagem" name="imagem"><br>
        <label for="titulo">Titulo:</label>
        <input type="text" id="titulo" name="titulo"><br>
        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao"><br>
        <label for="preco">Preço:</label>
        <input type="text" id="preco" name="preco"><br>
        <label for="whatsapp_contato">Número de WhatsApp:</label>
        <input type="text" id="whatsapp_contato" name="whatsapp_contato"><br>
        <input type="submit" value="Adicionar">
    </form>
    <p>
        <a href="produtos.php">Voltar para a lista de produtos</a>
    </p><br>
    
    <footer class="footer-container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>