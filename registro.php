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

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#00875e">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- CSS  -->
    <link href="min/plugin-min.css" type="text/css" rel="stylesheet">
    <link href="min/custom-min.css" type="text/css" rel="stylesheet">
    <link href="css/registro.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Cadastro</title>
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
        <input type="checkbox" class="filled-in" checked="checked" id="accept-terms" name="accept-terms">
        <span>Eu concordo com os</span> <a href="termos.php" target="_blank">Termos e Condições</a>.
    </label>
    <span class="help-block"><?php echo $terms_err; ?></span>
</div>
                <div class="form-group">
                    <input type="submit" class="btn-primary" value="Registrar">
                    <input type="reset" class="btn-default" value="Limpar">
                </div>
                <p>Já tem uma conta? <a href="login.php">Faça o login aqui</a>.</p>
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
    <!-- Adicione o jQuery (necessário para alguns componentes do Materialize) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Adicione o Materialize JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="min/plugin-min.js"></script>
    <script src="min/custom-min.js"></script>

</body>

</html>
