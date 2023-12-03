<?php
// Arquivo de conexão com o banco de dados
include 'includes/conexao.php';

// Inicialize as variáveis
$nome = $matricula = $senha = $foto_perfil = $nome_err = $matricula_err = $senha_err = $terms_err = $foto_perfil_err = '';

// Processa os dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se a caixa de seleção dos Termos e Condições está marcada
    if (empty($_POST["accept-terms"])) {
        $terms_err = "Você deve concordar com os Termos e Condições para se registrar.";
    } else {
        // Valida a foto de perfil
        if (!empty($_FILES['foto_perfil']['name'])) {
            $foto_perfil = $_FILES['foto_perfil']['name'];
            $foto_temp = $_FILES['foto_perfil']['tmp_name'];
            $upload_dir = 'imagens/'; // Substitua pelo diretório correto

            if (move_uploaded_file($foto_temp, $upload_dir . $foto_perfil)) {
                // A foto de perfil foi enviada com sucesso, você pode salvar o nome do arquivo no banco de dados.
            } else {
                $foto_perfil_err = "Erro ao fazer o upload da foto de perfil.";
                $foto_perfil = ''; // Limpa o nome do arquivo em caso de erro no upload.
            }
        }

        // Valida o nome
        if (empty(trim($_POST["nome"]))) {
            $nome_err = "Por favor, insira o nome.";
        } else {
            $nome = trim($_POST["nome"]);
        }

        // Valida a matrícula
        if (empty(trim($_POST["matricula"]))) {
            $matricula_err = "Por favor, insira a matrícula.";
        } else {
            // Verifica se a matrícula já existe no banco de dados
            $sql = "SELECT id FROM usuarios WHERE matricula = :matricula";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bindParam(":matricula", $param_matricula, PDO::PARAM_STR);

                $param_matricula = trim($_POST["matricula"]);

                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        $matricula_err = "Esta matrícula já está em uso.";
                    } else {
                        $matricula = trim($_POST["matricula"]);
                    }
                } else {
                    echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
                }
                unset($stmt);
            }
        }

        // Validação da senha
        if (empty(trim($_POST["senha"]))) {
            $senha_err = "Por favor, insira a senha.";
        } elseif (strlen(trim($_POST["senha"])) < 6) {
            $senha_err = "A senha deve ter pelo menos 6 caracteres.";
        } else {
            $senha = trim($_POST["senha"]);
        }

        // Verifica erros de entrada antes de inserir no banco de dados
        if (empty($nome_err) && empty($matricula_err) && empty($senha_err) && empty($terms_err) && empty($foto_perfil_err)) {
            // Prepara uma instrução de inserção
            $sql = "INSERT INTO usuarios (nome, matricula, senha, foto_perfil) VALUES (:nome, :matricula, :senha, :foto_perfil)";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bindParam(":nome", $param_nome, PDO::PARAM_STR);
                $stmt->bindParam(":matricula", $param_matricula, PDO::PARAM_STR);
                $stmt->bindParam(":senha", $param_senha, PDO::PARAM_STR);
                $stmt->bindParam(":foto_perfil", $param_foto_perfil, PDO::PARAM_STR);

                $param_nome = $nome;
                $param_matricula = $matricula;
                $param_senha = password_hash($senha, PASSWORD_DEFAULT); // Cria um hash de senha
                $param_foto_perfil = $foto_perfil;

                if ($stmt->execute()) {
                    // Redireciona para a página de login após o registro
                    header("location: login.php");
                } else {
                    echo "Algo deu errado. Por favor, tente novamente mais tarde.";
                }
                unset($stmt);
            }
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
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Criar Usuário</title>
    <link rel="stylesheet" href="css/registro.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
        <nav>
            <ul>
            </ul>
        </nav>
    </header>
    
    <div class="container form-wrapper">
        <h2>Criar Usuário</h2>
        <p>Preencha este formulário para criar uma conta.</p><br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <div class="form-group <?php echo (!empty($foto_perfil_err)) ? 'has-error' : ''; ?>">
        <label>Foto de Perfil:</label>
        <input type="file" name="foto_perfil" accept="image/*">
        <span class="help-block"><?php echo $foto_perfil_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
        <label>Nome:</label>
        <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
        <span class="help-block"><?php echo $nome_err; ?></span>
    </div>
            <div class="form-group <?php echo (!empty($matricula_err)) ? 'has-error' : ''; ?>">
                <label>Matrícula</label>
                <input type="text" name="matricula" class="form-control" value="<?php echo $matricula; ?>">
                <span class="help-block"><?php echo $matricula_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($senha_err)) ? 'has-error' : ''; ?>">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" value="<?php echo $senha; ?>">
                <span class="help-block"><?php echo $senha_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($terms_err)) ? 'has-error' : ''; ?>">
                <label for="accept-terms">
                    <input type="checkbox" id="accept-terms" name="accept-terms" required>
                    Eu concordo com os <a href="termos.php" target="_blank">Termos e Condições</a>.
                </label>
                <span class="help-block"><?php echo $terms_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registrar">
                <input type="reset" class="btn btn-default" value="Limpar">
            </div>
            <p>Já tem uma conta? <a href="login.php">Faça o login aqui</a>.</p>
        </form>
    </div>
    
    <footer class="footer-container">
        <p>&copy; Classificados ICET - Projeto SUPER</p>
    </footer>
</body>
</html>
