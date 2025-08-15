<?php
session_start(); // Inicia a sessão

// Conexão com o banco de dados
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

// Recebe os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha

// Verifica se o email já está em uso
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email já está em uso
    header("Location: criarconta.php?error=1");
    exit;
}

// Insere o novo usuário no banco de dados
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senha);

if ($stmt->execute()) {
    // Usuário criado com sucesso
    $_SESSION['usuario'] = $email; // Define a sessão do usuário
    header("Location: escolher_tipo.php"); // Redireciona para a página de escolha do tipo
    exit;
} else {
    // Erro ao criar o usuário
    echo "Erro: " . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>
