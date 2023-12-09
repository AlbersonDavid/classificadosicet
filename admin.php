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
    <link href="css/admin.css" type="text/css" rel="stylesheet">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Administradores</title>
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
                        <li><a href="registro.php">Cadastro</a></li>
                    </ul>
                    <ul id="nav-mobile" class="side-nav">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="registro.php">Cadastro</a></li>
                    </ul>
                    <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
                </div>
            </div>
        </nav>
    </div>

    <!--Work-->
    <div class="section scrollspy" id="work">
        <div class="containers form-wrapper">
        <?php
            include 'includes/conexao.php';

            session_start(); // Inicie a sessão

            // Verifique se o administrador já está autenticado
            if (isset($_SESSION['admin_id'])) {
                $adminId = $_SESSION['admin_id'];

                // Consulte o banco de dados para obter as credenciais do administrador
                $query = "SELECT email FROM administradores WHERE id = :adminId";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':adminId', $adminId, PDO::PARAM_INT);
                $stmt->execute();

                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($admin) {
                    echo '<h2>Olá, Administrador!</h2>';
                    echo '<p>Email: ' . $admin['email'] . '</p>';
                    echo '<a href="dashboard.php">Acessar o painel de administração</a>';
                    exit(); // Mostrar informações do administrador autenticado e encerrar a página
                }
            }

            // Se o administrador não estiver autenticado, exiba o formulário de login
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST["email"];
                $senha = $_POST["senha"];

                $sql = "SELECT id FROM administradores WHERE email = :email AND senha = :senha";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        // Autenticação bem-sucedida, armazene o ID do administrador na sessão
                        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                        $_SESSION['admin_id'] = $admin['id'];

                        header('Location: dashboard.php');
                        exit();
                    } else {
                        echo '<p>Email ou senha incorretos.</p>';
                    }
                } else {
                    echo "Algo deu errado. Por favor, tente novamente mais tarde.";
                }
            }
            ?>
            <h2>Login do Administrador</h2><br>
            <form method="post">
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" class="form-control">
                </div>
                
                <input type="submit" class="btn-primary" value="Entrar">
                
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