<?php
// Define o cabeçalho para indicar que a resposta é um JSON
header('Content-Type: application/json');

// Inicia a sessão
session_start();

// Inclui o arquivo de conexão com o banco de dados
// Certifique-se de que o caminho está correto
include('../conexao.php');

$response = [
    'success' => false,
    'message' => ''
];

// Garante que a requisição seja via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e limpa os dados enviados
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $caminho_foto_perfil = 'img/default-avatar.png'; // Foto padrão

    // Validação inicial
    if (empty($nome) || empty($email) || empty($senha)) {
        $response['message'] = "Todos os campos são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "O formato do email é inválido.";
    } else {
        // Prepara a consulta para verificar se o email ou nome já existem
        $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR nome = ?");
        $stmt_check->bind_param("ss", $email, $nome);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $response['message'] = "Email ou nome de usuário já está em uso.";
        } else {
            // Processa o upload da foto se existir
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $diretorio_uploads = 'uploads/perfil/';
                $nome_arquivo = uniqid() . '_' . basename($_FILES['foto']['name']);
                $caminho_completo = $diretorio_uploads . $nome_arquivo;

                if (!is_dir($diretorio_uploads)) {
                    mkdir($diretorio_uploads, 0777, true);
                }

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_completo)) {
                    $caminho_foto_perfil = $caminho_completo;
                } else {
                    $response['message'] = "Houve um erro ao enviar sua foto.";
                }
            }

            // Se a validação e o upload não geraram erros
            if (empty($response['message'])) {
                // Criptografa a senha com segurança
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                // Insere o novo usuário no banco de dados
                $stmt_insert = $conn->prepare("INSERT INTO usuarios (nome, email, senha, foto_perfil) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("ssss", $nome, $email, $senha_hash, $caminho_foto_perfil);

                if ($stmt_insert->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Cadastro realizado com sucesso!";
                } else {
                    $response['message'] = "Erro ao criar a conta. Tente novamente.";
                }
                $stmt_insert->close();
            }
        }
        $stmt_check->close();
    }
} else {
    // Resposta para requisições com método inválido
    http_response_code(405); // Método não permitido
    $response['message'] = "Método de requisição inválido.";
}

// Fecha a conexão com o banco de dados
if (isset($conn) && $conn) {
    $conn->close();
}

// Retorna a resposta final em formato JSON
echo json_encode($response);
exit();