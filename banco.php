<?php
$host = "localhost";
$user = "root"; // Altere se necessário
$pass = "";     // Altere se tiver senha
$dbname = "augebit";

// Criar conexão
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
