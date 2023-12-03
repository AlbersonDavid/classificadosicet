<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Administradores</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Login do Administrador</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
    </header>

    <div id="box">
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
        <form method="post">
            <h2>Login Administrador</h2>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha"><br>
            <input type="submit" value="Entrar">
        </form>
    </div>

    <footer class="footer-container">
        <p>&copy; Classificados ICET - Projeto SUPER</p>
    </footer>
</body>
</html>
