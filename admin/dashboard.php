<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <a href=".classificadosicet/index.php"><img src="assets/logo.png" alt="Logo da Página"></a>
        </div>
    </header>
    <div class="container">
        <h1>Dashboard do Admin</h1>

        <?php
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "classificadosicet";

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Estatísticas de usuários
            $queryUsuarios = "SELECT COUNT(*) as totalUsuarios FROM usuarios";
            $resultadoUsuarios = $conn->query($queryUsuarios);
            $dadosUsuarios = $resultadoUsuarios->fetch(PDO::FETCH_ASSOC);

            // Estatísticas de produtos
            $queryProdutos = "SELECT COUNT(*) as totalProdutos FROM produtos";
            $resultadoProdutos = $conn->query($queryProdutos);
            $dadosProdutos = $resultadoProdutos->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
        ?>

        <br><h2>Estatísticas de Usuários</h2>
        <p>Total de Usuários: <?php echo $dadosUsuarios['totalUsuarios']; ?></p>

        <h2>Estatísticas de Produtos</h2>
        <p>Total de Produtos: <?php echo $dadosProdutos['totalProdutos']; ?></p>

        <h2>Gráfico de Usuários e Produtos</h2>
        <canvas id="grafico"></canvas>

        <h2>Buscar Usuário</h2>
        <form method="POST" action="buscar_usuario.php">
            <input type="text" name="termo_busca" placeholder="Nome ou Matrícula">
            <button type="submit">Buscar</button>
        </form>

        <h2>Excluir Usuário e Produtos</h2>
        <form method="POST" action="excluir_usuario.php">
            <input type="text" name="id_usuario" placeholder="ID do Usuário">
            <button type="submit">Excluir</button>
        </form>
        
        <script>
            // Dados para o gráfico
            var dados = {
                labels: ['Usuários', 'Produtos'],
                datasets: [{
                    data: [<?php echo $dadosUsuarios['totalUsuarios']; ?>, <?php echo $dadosProdutos['totalProdutos']; ?>],
                    backgroundColor: ['blue', 'darkblue'],
                }]
            };

            // Configurações do gráfico
            var config = {
                type: 'pie', 
                data: dados,
                options: {
          
                }
            };

            var grafico = new Chart(document.getElementById('grafico'), config);
        </script>
    </div>
    <br>
    <footer class="footer-container">
        <p>&copy; Classificados ICET - Projeto SUPER</p>
    </footer>
</body>
</html>
