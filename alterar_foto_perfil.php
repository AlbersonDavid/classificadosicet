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

// Dados do usuário
$matricula = $_SESSION["matricula"];
$fotoPerfil = ''; // Variável para armazenar o nome da foto de perfil atual

// Verifica se o usuário já possui uma foto de perfil
$sqlFotoPerfil = "SELECT foto_perfil FROM usuarios WHERE matricula = :matricula";
$stmtFotoPerfil = $conn->prepare($sqlFotoPerfil);
$stmtFotoPerfil->bindValue(":matricula", $matricula, PDO::PARAM_STR);
if ($stmtFotoPerfil->execute()) {
    $resultFotoPerfil = $stmtFotoPerfil->fetch(PDO::FETCH_ASSOC);
    $fotoPerfil = $resultFotoPerfil['foto_perfil'];
}

// Processa a atualização da foto de perfil quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['nova_foto_perfil']['name'])) {
        $novaFotoPerfil = $_FILES['nova_foto_perfil']['name'];
        $fotoTemp = $_FILES['nova_foto_perfil']['tmp_name'];
        $uploadDir = 'imagens/'; // Diretório onde as fotos de perfil são armazenadas

        if (move_uploaded_file($fotoTemp, $uploadDir . $novaFotoPerfil)) {
            // A nova foto de perfil foi enviada com sucesso, você pode salvar o nome do arquivo no banco de dados.
            
            // Atualiza a foto de perfil no banco de dados
            $sqlUpdateFotoPerfil = "UPDATE usuarios SET foto_perfil = :foto_perfil WHERE matricula = :matricula";
            $stmtUpdateFotoPerfil = $conn->prepare($sqlUpdateFotoPerfil);
            $stmtUpdateFotoPerfil->bindValue(":foto_perfil", $novaFotoPerfil, PDO::PARAM_STR);
            $stmtUpdateFotoPerfil->bindValue(":matricula", $matricula, PDO::PARAM_STR);

            if ($stmtUpdateFotoPerfil->execute()) {
                $fotoPerfil = $novaFotoPerfil; // Atualiza a variável de foto de perfil com o novo nome do arquivo
            } else {
                echo "Erro ao atualizar a foto de perfil no banco de dados.";
            }
        } else {
            echo "Erro ao fazer o upload da nova foto de perfil.";
        }
    } else {
        echo "Por favor, selecione um arquivo para enviar como foto de perfil.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Foto de Perfil</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
        <nav>
            <ul>
                <li><a href="perfil.php"><img src="assets/user-icon.png" alt="Perfil"> Perfil</a></li>
                <li><a href="logout.php"><img src="assets/logout-icon.png" alt="Logout"> Sair</a></li>
            </ul>
        </nav>
    </header>

    <br><h1>Alterar Foto de Perfil</h1>

    <main>
        <section id="user-profile">
            <h2>Bem-vindo, <?php echo $matricula; ?></h2>
            
            <h3>Foto de Perfil Atual:</h3>
            <?php if (!empty($fotoPerfil)) { ?>
                <img src="imagens/<?php echo $fotoPerfil; ?>" alt="Foto de Perfil Atual">
            <?php } else { ?>
                <p>Você ainda não possui uma foto de perfil.</p>
            <?php } ?>
            
            <h3>Enviar Nova Foto de Perfil:</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="nova_foto_perfil" accept="image/*">
                <input type="submit" value="Enviar Nova Foto">
            </form>
            
            <p><a href="perfil.php">Voltar para o perfil</a></p>
        </section>
    </main>

    <footer class="footer-container">
        <p>&copy; Classificados ICET - Projeto SUPER</p>
    </footer>
</body>
</html>
