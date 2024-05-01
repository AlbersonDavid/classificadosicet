<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Inicializa as variáveis
$matricula = $matricula_err = "";

// Processa os dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valida a matrícula
    if (empty(trim($_POST["matricula"]))) {
        $matricula_err = "Por favor, insira a matrícula.";
    } else {
        $matricula = trim($_POST["matricula"]);
    }

    // Verifica os erros de entrada antes de prosseguir
    if (empty($matricula_err)) {
        // Consulta o SQL para verificar se a matrícula existe
        $sql = "SELECT id, matricula FROM usuarios WHERE matricula = :matricula";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":matricula", $param_matricula, PDO::PARAM_STR);

            $param_matricula = $matricula;

            // Executa a consulta SQL
            if ($stmt->execute()) {
                // Verifica se a matrícula existe
                if ($stmt->rowCount() == 1) {
                    // Redireciona para a página de redefinição de senha, passando a matrícula como parâmetro
                    header("location: redefinir_senha.php?matricula=" . $matricula);
                    exit;
                } else {
                    // Exibe uma mensagem de erro se a matrícula não existe
                    $matricula_err = "Nenhuma conta encontrada com essa matrícula.";
                }
            } else {
                echo "Oops! Algo deu errado com a execução da consulta SQL: " . implode(" ", $stmt->errorInfo());
            }
            unset($stmt);
        } else {
            echo "Oops! Algo deu errado com a preparação da consulta SQL.";
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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <h2>Recuperar Senha</h2>
    <p>Informe sua matrícula para recuperar sua senha.</p>

    <!-- Formulário de recuperação de senha -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($matricula_err)) ? 'has-error' : ''; ?>">
            <label>Matrícula:</label>
            <input type="text" name="matricula" required>
            <span class="help-block"><?php echo $matricula_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" value="Recuperar Senha">
        </div>
    </form>
</body>

</html>
