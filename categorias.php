<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Categorias</title>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <link rel="stylesheet" href="css/categorias.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
    </header>
    
    <form method="post" action="pesquisa.php" class="search-form">
        <input type="text" name="pesquisa" placeholder="Pesquisar por título ou descrição">
        <input type="submit" value="Pesquisar">
    </form>

    <section id="categorias">
    <br><h2>Categorias</h2><br>
    <div class="category-grid">
        <div class="category">
            <a href="produtos.php?categoria=Livros%20e%20Materiais%20Acadêmicos">Livros e Materiais Acadêmicos</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Moradia%20Estudantil">Moradia Estudantil</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Equipamentos%20e%20Eletrônicos">Equipamentos e Eletrônicos</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Serviços%20e%20Aulas">Serviços e Aulas</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Empregos%20e%20Estágios">Empregos e Estágios</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Eventos%20e%20Grupos%20de%20Estudo">Eventos e Grupos de Estudo</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Móveis%20e%20Itens%20Domésticos">Móveis e Itens Domésticos</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Bicicletas%20e%20Meios%20de%20Transporte%20Alternativos">Bicicletas e Meios de Transporte Alternativos</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Roupas,%20Calçados%20e%20Acessórios">Roupas, Calçados e Acessórios</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Equipamentos%20e%20Instrumentos">Equipamentos e Instrumentos</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Diversos%20e%20Outros%20Itens">Diversos e Outros Itens</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Coisas%20para%20Casa">Coisas para Casa</a>
        </div>
        <div class="category">
            <a href="produtos.php?categoria=Outras%20Coisas">Outras Coisas</a>
        </div>
    </div>
</section>

   <br>
    <br>
    <p>
        <a href="produtos.php">Voltar para a lista de produtos</a>
        <a href="perfil.php">Voltar para perfil</a>
    </p><br>
    
    <footer class="footer-container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>