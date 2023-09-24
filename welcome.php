<!DOCTYPE html>
<html>
<head>
    <title>Bem-vindo ao Classificados ICET</title>
    <link rel="stylesheet" type="text/css" href="css/wecolme.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin/admin.php">Admin</a></li>
                <li><a href="registro.php">Criar Usuário</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
        <h1>Bem-vindo ao Classificados ICET</h1>
    </header>

    <main>
        <section id="welcome">
            <?php
            // Inicia a sessão
            session_start();

            // Verifica se o usuário está logado
            if (isset($_SESSION['matricula'])) {
                $nomeUsuario = $_SESSION['matricula'];
                echo "<h2>Bem-vindo, $nomeUsuario</h2>";
                echo "<p>Seja bem-vindo ao Marketplace Universitário do ICET. Você pode começar a navegar e comprar produtos ou cadastrar seus próprios produtos para venda.</p>";
                echo "<p>Explore as seguintes opções:</p>";
                echo '<ul>';
                echo '<li><a href="categorias.php">Ver Categorias</a></li>';
                echo '<li><a href="produtos.php">Ver Produtos</a></li>';
                echo '<li><a href="adicionar.php">Adicionar Produto</a></li>';
                echo '<li><a href="perfil.php">Perfil do Usuário</a></li>';
                echo '</ul>';
            } else {
                echo "<h2>Bem-vindo, Visitante</h2>";
                echo "<p>Seja bem-vindo ao Marketplace Universitário do ICET. Você pode começar a navegar e comprar produtos ou cadastrar seus próprios produtos para venda.</p>";
                echo "<p>Para acessar todas as funcionalidades, por favor, <a href='login.php'>faça login</a> ou <a href='registro.php'>crie uma conta</a>.</p>";
            }
            ?>
        </section>
    </main>

     <footer class="footer-container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>