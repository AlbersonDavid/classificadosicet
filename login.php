<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Inicializa as variáveis
$matricula = $senha = '';
$matricula_err = $senha_err = '';

// Processa os dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valida a matrícula
    if (empty(trim($_POST["matricula"]))) {
        $matricula_err = "Por favor, insira a matrícula.";
    } else {
        $matricula = trim($_POST["matricula"]);
    }

    // Valida a senha
    if (empty(trim($_POST["senha"]))) {
        $senha_err = "Por favor, insira a senha.";
    } else {
        $senha = trim($_POST["senha"]);
    }

    // Verifica os erros de entrada antes de autenticar o usuário
    if (empty($matricula_err) && empty($senha_err)) {
        // Consulta o SQL para verificar o usuário e a senha
        $sql = "SELECT id, matricula, senha FROM usuarios WHERE matricula = :matricula";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":matricula", $param_matricula, PDO::PARAM_STR);

            $param_matricula = $matricula;

            if ($stmt->execute()) {
                // Verifica se a matrícula existe
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $matricula = $row["matricula"];
                        $hashed_senha = $row["senha"];
                        if (password_verify($senha, $hashed_senha)) {
                            // A senha é correta
                            session_start();

                            // Armazena os dados na sessão
                            $_SESSION["id"] = $id;
                            $_SESSION["matricula"] = $matricula;
                            $_SESSION["usuario_id"] = $id; // Adicione esta linha

                            // Redireciona para a página de perfil
                            header("location: perfil.php");
                        } else {
                            // Exibe uma mensagem de erro de senha incorreta
                            $senha_err = "A senha que você inseriu não é válida.";
                        }
                    }
                } else {
                    // Exibe uma mensagem de erro se a matrícula não existe
                    $matricula_err = "Nenhuma conta encontrada com essa matrícula.";
                }
            } else {
                echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            unset($stmt);
        }
    }

    // Fecha a conexão
    unset($conn);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
        <nav>
            <ul>
                <li><a href="admin.php"><img src="assets/admin-icon.png" alt="Admin"> Admin</a></li>
                <li><a href="registro.php"><img src="assets/user-icon.png" alt="Criar Usuário"> Criar Usuário</a></li>
                <li><a href="login.php"><img src="assets/login-icon.png" alt="Login"> Login</a></li>
            </ul>
        </nav>
    </header>
    
     <div class="container form-wrapper">
        <h2>Login</h2>
        <p>Preencha suas credenciais para fazer login.</p><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($matricula_err)) ? 'has-error' : ''; ?>">
                <label>Matrícula</label>
                <input type="text" name="matricula" class="form-control" value="<?php echo $matricula; ?>">
                <span class="help-block"><?php echo $matricula_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($senha_err)) ? 'has-error' : ''; ?>">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control">
                <span class="help-block"><?php echo $senha_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Não tem uma conta? <a href="registro.php">Registre-se agora</a>.</p>
        </form>
    </div>
    
    <footer class="footer-container">
            <p>&copy; Classificados ICET - Projeto SUPER </p>
    </footer>
</body>
</html>
