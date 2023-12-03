<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/Icon.png" type="image/png">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar/dist/fullcalendar.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar/dist/fullcalendar.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/logoclassificados.png" alt="Logo da Página"></a>
        </div>
        
        <?php
        session_start();

        if (isset($_SESSION['admin_id'])) {
            $adminId = $_SESSION['admin_id'];

            $host = "localhost";
            $user = "root";
            $pass = "";
            $dbname = "classificadosicet";

            try {
                $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $queryAdmin = "SELECT email FROM administradores WHERE id = :adminId";
                $stmt = $conn->prepare($queryAdmin);
                $stmt->bindParam(':adminId', $adminId, PDO::PARAM_INT);
                $stmt->execute();
                $adminInfo = $stmt->fetch(PDO::FETCH_ASSOC);

                // Exibe as credenciais do administrador
                echo '<div class="admin-credentials">';
                echo '<p>Admin: Administrador</p>';
                echo '<p>Email: ' . $adminInfo['email'] . '</p>';
                echo '</div>';
            } catch (PDOException $e) {
                echo "Erro na conexão: " . $e.getMessage();
            }
        } else {
            // O administrador não está logado, redirecione para a página de login
            header('Location: login.php');
            exit();
        }
        ?>
    </header>
    <aside class="sidebar">
        <ul>
            <li><a href="#estatisticas">Estatísticas</a></li>
            <li><a href="#produtos">Produtos</a></li>
            <li><a href="#usuarios">Usuários</a></li>
            <li><a href="#calendario">Calendário</a></li>
        </ul>
    </aside>
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

        <section id="estatisticas">
            <h2>Estatísticas de Usuários</h2>
            <p>Total de Usuários: <?php echo $dadosUsuarios['totalUsuarios']; ?></p>

            <h2>Estatísticas de Produtos</h2>
            <p>Total de Produtos: <?php echo $dadosProdutos['totalProdutos']; ?></p>
            
            <h2>Gráfico de Usuários e Produtos</h2>
           <canvas id="grafico" width="200" height="150"></canvas>
        </section>

        <section id="calendario">
            <h2>Calendário</h2>
            <div id="calendario"></div>
        </section>

        <section id="produtos">
            <h2>Usuários Conectados</h2>
            <ul id="lista-usuarios-conectados"></ul>
        </section>

        <section id="usuarios">
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
        </section>
        
               <script>
            // Dados para o gráfico
            var dados = {
                labels: ['Usuários', 'Produtos'],
                datasets: [{
                    data: [<?php echo $dadosUsuarios['totalUsuarios']; ?>, <?php echo $dadosProdutos['totalProdutos']; ?>],
                    backgroundColor: ['orange', 'blue'],
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
        
        <script>
            // Inicialize o calendário
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendario');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth'
                });
                calendar.render();
            });
        </script>
    </div>
    <br>
    <footer class="footer-container">
        <p>&copy; Classificados ICET - Projeto SUPER</p>
    </footer>
</body>
</html>