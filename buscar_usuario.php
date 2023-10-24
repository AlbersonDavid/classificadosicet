<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "classificadosicet";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $termo_busca = $_POST["termo_busca"];
    

    $query = "SELECT * FROM usuarios WHERE nome LIKE :termo OR matricula LIKE :termo";
    
    try {
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':termo', "%$termo_busca%", PDO::PARAM_STR);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID: " . $row["id"] . "<br>";
            echo "Nome: " . $row["nome"] . "<br>";
            echo "Matrícula: " . $row["matricula"] . "<br>";

            echo "<br>";
        }
    } catch (PDOException $e) {
        echo "Erro na busca: " . $e->getMessage();
    }
}
?>
