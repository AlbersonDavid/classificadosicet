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

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00875e">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- CSS  -->
    <link href="min/plugin-min.css" type="text/css" rel="stylesheet">
    <link href="min/custom-min.css" type="text/css" rel="stylesheet">
    <link href="css/login.css" type="text/css" rel="stylesheet">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Login</title>
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
                        <li><a href="admin.php">Admin</a></li>
                    </ul>
                    <ul id="nav-mobile" class="side-nav">
                        <li><a href="admin.php">Admin</a></li>
                    </ul>
                    <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
                </div>
            </div>
        </nav>
    </div>
    
    <!--Work-->
    <div class="section scrollspy" id="work">
        <div class="containers form-wrapper">
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
                    <input type="submit" class="btn-primary" value="Login">
                </div>
                <p>Não tem uma conta? <a href="registro.php">Registre-se agora</a>.</p>
                <p>Esqueceu sua senha? <a href="recuperar_senha.php">Clique aqui</a> para recuperá-la.</p>
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