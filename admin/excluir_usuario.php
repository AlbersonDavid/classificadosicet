<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "classificadosicet";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_usuario = $_POST["id_usuario"];

        $conn->beginTransaction();
        
        try {
            // Exclui produtos associados ao usuário
            $queryExcluirProdutos = "DELETE FROM produtos WHERE id_usuario = :id_usuario";
            $stmtExcluirProdutos = $conn->prepare($queryExcluirProdutos);
            $stmtExcluirProdutos->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmtExcluirProdutos->execute();
            
            // Exclui o usuário
            $queryExcluirUsuario = "DELETE FROM usuarios WHERE id = :id_usuario";
            $stmtExcluirUsuario = $conn->prepare($queryExcluirUsuario);
            $stmtExcluirUsuario->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmtExcluirUsuario->execute();
            
            $conn->commit();
            
            echo "Usuário e seus produtos foram excluídos com sucesso!";
        } catch (PDOException $e) {
            $conn->rollback();
            echo "Erro na exclusão: " . $e->getMessage();
        }
    }
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>