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

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicialize as variáveis
    $novaSenha = $confirmarSenha = "";
    $novaSenha_err = $confirmarSenha_err = "";

    // Processa os dados do formulário
    $novaSenha = $_POST["nova_senha"];
    $confirmarSenha = $_POST["confirmar_senha"];

    // Valida a nova senha
    if (empty($novaSenha)) {
        $novaSenha_err = "Por favor, insira a nova senha.";
    } elseif (strlen($novaSenha) < 6) {
        $novaSenha_err = "A senha deve ter pelo menos 6 caracteres.";
    }

    // Valida a confirmação da senha
    if (empty($confirmarSenha)) {
        $confirmarSenha_err = "Por favor, confirme a nova senha.";
    } elseif ($novaSenha != $confirmarSenha) {
        $confirmarSenha_err = "As senhas não coincidem.";
    }

    // Verifica erros de entrada antes de alterar a senha
    if (empty($novaSenha_err) && empty($confirmarSenha_err)) {
        // Atualiza a senha no banco de dados
        $matricula = $_SESSION["matricula"];
        $senha_hashed = password_hash($novaSenha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET senha = :senha WHERE matricula = :matricula";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":senha", $senha_hashed, PDO::PARAM_STR);
        $stmt->bindParam(":matricula", $matricula, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Redireciona de volta ao perfil com uma mensagem de sucesso
            header("location: perfil.php");
        } else {
            echo "Erro ao atualizar a senha.";
        }
        unset($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="css/alterar_senha.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logo.png" alt="Logo da Página"></a>
        </div>
        <nav>
            <ul>
                <li><a href="categorias.php"><img src="assets/categorias-icon.png" alt="Categorias"> Categorias</a></li>
                <li><a href="perfil.php"><img src="assets/perfil-icon.png" alt="Perfil"> Perfil</a></li>
                <li><a href="logout.php"><img src="assets/logout-icon.png" alt="Logout"> Sair</a></li>
            </ul>
        </nav>
    </header>

    <br><h1>Alterar Senha</h1>

    <main>
        <section id="change-password">
            <h3>Nova Senha</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Nova Senha:</label>
                    <input type="password" name="nova_senha" required>
                    <span class="help-block"><?php echo $novaSenha_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirme a Nova Senha:</label>
                    <input type="password" name="confirmar_senha" required>
                    <span class="help-block"><?php echo $confirmarSenha_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" value="Alterar Senha">
                </div>
            </form>
        </section>
    </main>

    <footer class="footer-container">
        <p>&copy; Classificados ICET - Projeto SUPER</p>
    </footer>
</body>
</html>
