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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="css/alterar_senha.css">
</head>
<body>
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
            <input type="submit" value="Redefinir Senha">
        </div>
    </form>
</body>
</html>