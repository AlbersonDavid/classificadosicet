<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Inicializa as variáveis
$matricula = $novaSenha = $confirmarSenha = "";
$novaSenha_err = $confirmarSenha_err = "";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém a matrícula da URL
    $matricula = $_GET["matricula"];

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
        $senha_hashed = password_hash($novaSenha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET senha = :senha WHERE matricula = :matricula";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":senha", $senha_hashed, PDO::PARAM_STR);
        $stmt->bindParam(":matricula", $matricula, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Redireciona para a página de login com uma mensagem de sucesso
            header("location: login.php?senha_redefinida=sim");
            exit;
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
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00875e">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- CSS  -->
    <link href="min/plugin-min.css" type="text/css" rel="stylesheet">
    <link href="min/custom-min.css" type="text/css" rel="stylesheet">
    <link href="css/redefinesenha.css" type="text/css" rel="stylesheet">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Redefiner Senha</title>
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
                        <li><a href="login.php">Login</a></li>
                    </ul>
                    <ul id="nav-mobile" class="side-nav">
                        <li><a href="login.php">Login</a></li>
                    </ul>
                    <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
                </div>
            </div>
        </nav>
    </div>
    
    <!--Work-->
    <div class="section scrollspy" id="work">
        <div class="containers form-wrapper">
        <h2>Redefinir Senha</h2>
    <p>Por favor, insira sua nova senha.</p>

    <!-- Formulário de redefinição de senha -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?matricula=" . $matricula; ?>" method="post">
        <div class="form-group <?php echo (!empty($novaSenha_err)) ? 'has-error' : ''; ?>">
            <label>Nova Senha:</label>
            <input type="password" name="nova_senha" required>
            <span class="help-block"><?php echo $novaSenha_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirmarSenha_err)) ? 'has-error' : ''; ?>">
            <label>Confirme a Nova Senha:</label>
            <input type="password" name="confirmar_senha" required>
            <span class="help-block"><?php echo $confirmarSenha_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn-primary value="Redefinir Senha">
        </div>
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
    <script src="min/plugin-min.js"></script>
    <script src="min/custom-min.js"></script>

</body>

</html>