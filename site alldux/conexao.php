<?php
$host = '127.0.0.1:3306'; // Altere para o seu host
$db = 'u393630075_database'; // Altere para o seu banco de dados
$user = 'u393630075_admin'; // Altere para o seu usuário do banco
$pass = 'Gdml22052007$'; // Altere para a sua senha do banco

// Cria a conexão
$conn = new mysqli($host, $user, $pass, $db);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
