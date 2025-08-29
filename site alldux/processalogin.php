<?php
// Define que o conteúdo da resposta é JSON
header('Content-Type: application/json');

// Iniciar a sessão
session_start();

// Conectar ao banco de dados
include('conexao.php');

$response = [
    'success' => false,
    'message' => ''
];

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validação básica dos campos
    if (empty($email) || empty($senha)) {
        $response['message'] = "Preencha todos os campos.";
    } else {
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

                // Definir a resposta de sucesso
                $response['success'] = true;
                $response['message'] = "Login bem-sucedido!";

            } else {
                // Senha incorreta
                $response['message'] = "Email ou senha incorretos.";
            }
        } else {
            // Usuário não encontrado
            $response['message'] = "Email ou senha incorretos.";
        }
    }
} else {
    // Se o método não for POST, retorne um erro
    http_response_code(405);
    $response['message'] = "Método de requisição inválido.";
}

// Fechamento da conexão (boa prática, embora o script vá terminar)
if (isset($conn) && $conn) {
    $conn->close();
}

// Retorna a resposta final em formato JSON
echo json_encode($response);
exit();