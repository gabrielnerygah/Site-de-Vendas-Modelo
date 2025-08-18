<?php
// Iniciar a sessão
session_start();

// Conectar ao banco de dados
include('conexao.php');

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Buscar o usuário no banco de dados
    $stmt = $conn->prepare("SELECT id, nome, senha, tipo, foto_perfil FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verificar se a senha está correta
        if (password_verify($senha, $usuario['senha'])) {
            // Senha correta: Armazenar dados do usuário na sessão
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nome_usuario'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $usuario['tipo'];
            $_SESSION['foto_perfil'] = $usuario['foto_perfil'];

            // Redirecionar para a página index
            header("Location: index.php");
            exit(); // Para a execução do script após o redirecionamento
        }
    }

    // Se o login falhou (usuário não encontrado ou senha incorreta), redireciona de volta com erro
    // É mais seguro usar uma mensagem genérica para não informar se o email existe ou não
    header("Location: login.php?error=Email ou senha incorretos.");
    exit();

} else {
    // Se alguém tentar acessar este arquivo diretamente sem enviar o formulário, redireciona para o login
    header("Location: login.php");
    exit();
}

// Nota: A conexão será fechada pelo PHP quando o script terminar.
// Não é estritamente necessário chamar $conn->close() aqui, pois o exit() finaliza o script.
?>