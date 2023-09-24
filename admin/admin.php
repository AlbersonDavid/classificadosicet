<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login do Administrador</title>
    <link rel="stylesheet" href="./css/admin.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="./index.php"><img src="assets/logo.png" alt="Logo da Página"></a>
        </div>
    </header>

    <h1>Login do Administrador</h1>
    <?php
    // Verifique se o formulário foi enviado
    if (isset($_POST['usuario']) && isset($_POST['senha'])) {
        // Verifique as credenciais do administrador (substitua com suas próprias credenciais)
        if ($_POST['usuario'] === 'super' && $_POST['senha'] === 'ufam') {
            // Redirecionar para a página de 
            header('Location: dashboard.php');
            exit;
        } else {
            // Exibir mensagem de erro
            echo '<p>Nome de usuário ou senha incorretos.</p>';
        }
    }
    ?>
    <form method="post">
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario"><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha"><br>
        <input type="submit" value="Entrar">
    </form>
    
    <footer class="footer-container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>