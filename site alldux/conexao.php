<?php
$host = '127.0.0.1:3306'; // Altere para o seu host
$db = 'u393630075_database'; // Altere para o seu banco de dados
$user = 'u393630075_admin'; // Altere para o seu usuário do banco
$pass = 'Gdml22052007$'; // Altere para a sua senha do banco

$conn = new mysqli("127.0.0.1:3306", "u393630075_admin", "Gdml22052007$", "u393630075_database");

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}
?>
