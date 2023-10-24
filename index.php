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
     <script type="text/javascript" src="js/main.js"></script>
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

    
  <div class="banner">
    <img src="/imagens/banner.jpg" alt="Banner Ufam">
</div>
    
    <section id="recent-products">
        <h2>Produtos Recentes</h2>
        <div class="slick-carousel">
            <?php
            foreach ($recentProducts as $product) {
                echo '<div class="carousel-item">';
                echo '<img src="imagens/' . $product['imagem'] . '" alt="' . $product['titulo'] . '">';
                echo '<h3>' . $product['titulo'] . '</h3>';
                echo '<p>Tipo: ' . ucfirst($product['tipo']) . '</p>';
                
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <div class="container">
        <section id="produtos">
            <br>
            <h2>Produtos</h2><br>
            <div class="grid-container">
                <?php
                // Verifica se uma categoria foi selecionada
                $categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;

                // Consulta o SQL para recuperar produtos por categoria ou todos os produtos
                $sqlProdutos = "SELECT produtos.*, usuarios.nome AS nome_usuario, usuarios.foto_perfil
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
        echo '<div class="grid-item">';
        echo '<img src="imagens/' . $produto['imagem'] . '" alt="Imagem do Produto">';
        echo '<h3>' . $produto['titulo'] . '</h3>';
        echo '<p>' . $produto['descricao'] . '</p>';
        echo '<p>Preço: R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
        echo '<p><strong>Vendedor:</strong> ' . $produto['nome_usuario'] . '</p>'; 
        echo '<p>Tipo: ' . ucfirst($produto['tipo']) . '</p>';
        echo '<div class="profile-icon-container">'; 
        echo '<img src="imagens/' . $produto['foto_perfil'] . '" alt="Perfil do Vendedor" class="profile-icon">';
        echo '</div>';
        echo '<a href="https://wa.me/' . $produto['whatsapp_contato'] . '?text=' . urlencode('Gostaria do Item: ' . $produto['titulo'] . ' - Preço: R$ ' . number_format($produto['preco'], 2, ',', '.')) . '" class="whatsapp-button" target="_blank"><img src="assets/whatsapp-icon.png" alt="WhatsApp"> Contatar via WhatsApp</a>';
        echo '</div>';
    }
} else {
    echo '<p>Nenhum produto encontrado.</p>';
}
                
                ?>
            </div>
        </section>
    </div>

    <div class="spacer"></div>
    

<button id="supportButton" class="support-button">Suporte</button>

<div id="supportModal" class="modal">
  <div class="modal-content">
    <span class="close-button">&times;</span>
    <h2>Entre em contato com o suporte</h2>
    <form id="supportForm" action="enviar_email.php" method="post" enctype="multipart/form-data">
      <label for="description">Descrição:</label>
      <textarea id="description" name="description" rows="4" required></textarea>
      <label for="screenshot">Captura de tela:</label>
      <input type="file" id="screenshot" name="screenshot" accept="image/*" required>
      <button type="submit">Enviar</button>
    </form>
  </div>
</div>
    
    <script>

function openSupportModal() {
    var modal = document.getElementById("supportModal");
    modal.style.display = "block";
}

function closeSupportModal() {
    var modal = document.getElementById("supportModal");
    modal.style.display = "none";
}


document.getElementById("supportButton").addEventListener("click", openSupportModal);


document.getElementsByClassName("close-button")[0].addEventListener("click", closeSupportModal);


window.addEventListener("click", function (event) {
    var modal = document.getElementById("supportModal");
    if (event.target === modal) {
        closeSupportModal();
    }
});

        
</script>

     <footer class="footer-container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>