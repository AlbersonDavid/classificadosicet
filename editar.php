<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Verifica se um ID de produto foi especificado na URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header('Location: produtos.php');
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $categoria = $_POST['categoria'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $whatsapp_contato = $_POST['whatsapp_contato']; 

    // Atualiza os dados no banco de dados
    $sql = "UPDATE produtos SET categoria = '$categoria', titulo = '$titulo', descricao = '$descricao', preco = '$preco', whatsapp_contato = '$whatsapp_contato' WHERE id = $id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Redireciona para a página de produtos
    header('Location: produtos.php');
    exit();
}

// Consulta o SQL para obter os detalhes do produto
$sql = "SELECT * FROM produtos WHERE id = $id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header('Location: produtos.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Produto</title>
    <link rel="stylesheet" type="text/css" href="css/editarstyle.css">
</head>
<body>
    <h1>Editar Produto</h1>
    <form method="post">
        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria">
            <option value="Livros e Materiais Acadêmicos" <?php if ($produto['categoria'] === 'Livros e Materiais Acadêmicos') echo 'selected'; ?>>Livros e Materiais Acadêmicos</option>
            
            <option value="Moradia Estudantil" <?php if ($produto['categoria'] === 'Moradia Estudantil') echo 'selected'; ?>>Moradia Estudantil</option>
            
            <option value="Equipamentos e Eletrônicos" <?php if ($produto['categoria'] === 'Equipamentos e Eletrônicos') echo 'selected'; ?>>Equipamentos e Eletrônicos</option>
            
            <option value="Serviços e Aulas" <?php if ($produto['categoria'] === 'Serviços e Aulas') echo 'selected'; ?>>Serviços e Aulas</option>
            
            <option value="Empregos e Estágios" <?php if ($produto['categoria'] === 'Empregos e Estágios') echo 'selected'; ?>>Empregos e Estágios</option>
            
            <option value="Eventos e Grupos de Estudo" <?php if ($produto['categoria'] === 'Eventos e Grupos de Estudo') echo 'selected'; ?>>Eventos e Grupos de Estudo</option>
            
            <option value="Móveis e Itens Domésticos" <?php if ($produto['categoria'] === 'Móveis e Itens Domésticos') echo 'selected'; ?>>Móveis e Itens Domésticos</option>
            
            <option value="Bicicletas e Meios de Transporte Alternativos" <?php if ($produto['categoria'] === 'Bicicletas e Meios de Transporte Alternativos') echo 'selected'; ?>>Bicicletas e Meios de Transporte Alternativos</option>
            
            <option value="Roupas, Calçados e Acessórios" <?php if ($produto['categoria'] === 'Roupas, Calçados e Acessórios') echo 'selected'; ?>>Roupas, Calçados e Acessórios</option>
            
            <option value="Equipamentos e Instrumentos" <?php if ($produto['categoria'] === 'Equipamentos e Instrumentos') echo 'selected'; ?>>Equipamentos e Instrumentos</option>
            
            <option value="Diversos e Outros Itens" <?php if ($produto['categoria'] === 'Diversos e Outros Itens') echo 'selected'; ?>>Diversos e Outros Itens</option>
            
            <option value="Coisas para Casa" <?php if ($produto['categoria'] === 'Coisas para Casa') echo 'selected'; ?>>Coisas para Casa</option>
           
        </select><br>
        <label for="titulo">Titulo:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo $produto['titulo']; ?>"><br>
        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" value="<?php echo $produto['descricao']; ?>"><br>
        <label for="preco">Preço:</label>
        <input type="text" id="preco" name="preco" value="<?php echo $produto['preco']; ?>"><br>
        <label for="whatsapp_contato">Número de WhatsApp:</label>
        <input type="text" id="whatsapp_contato" name="whatsapp_contato" value="<?php echo $produto['whatsapp_contato']; ?>"><br>
        <input type="submit" value="Salvar">
    </form>
    <p>
        <a href="produtos.php">Voltar para a lista de produtos</a>
    </p>
    
     <footer class="footer-container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>