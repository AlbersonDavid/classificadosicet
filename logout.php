<?php
// Inicia a sessão
session_start();

// Efetua o logout
session_destroy();

// Redireciona para a página de login
header("location: login.php");
exit;
?>
