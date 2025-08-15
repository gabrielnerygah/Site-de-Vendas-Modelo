<?php
session_start();

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

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); // Redireciona para login se não estiver logado
    exit;
}

// Recebe o tipo de usuário selecionado
$tipo = $_POST['tipo'];
$email = $_SESSION['usuario']; // Obtém o email do usuário da sessão

// Atualiza o tipo de usuário na tabela 'usuarios'
$sql = "UPDATE usuarios SET tipo = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $tipo, $email);

if ($stmt->execute()) {
    // Tipo de usuário atualizado com sucesso
    header("Location: index.php"); // Redireciona para a página inicial
    exit;
} else {
    // Erro ao atualizar o tipo de usuário
    echo "Erro: " . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>