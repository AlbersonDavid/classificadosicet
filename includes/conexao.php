<?php

$host = "localhost";
$user = "projetosufam";
$pass = "rxA@ompP3l2fe";
$dbname = "projetosufam_classificadosicet";

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Erro na conex達o: " . $e->getMessage();
}
?>
