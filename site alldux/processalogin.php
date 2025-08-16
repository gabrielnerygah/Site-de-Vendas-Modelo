<?php
// Iniciar a sessão
session_start();

// Conectar ao banco de dados
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $lembre_de_mim = isset($_POST['lembre_de_mim']);

    // Buscar o usuário no banco de dados
    $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verificar se a senha está correta
        if (password_verify($senha, $usuario['senha'])) {
            // Armazenar dados do usuário na sessão
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nome_usuario'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $usuario['tipo'];

            // Se "Lembre de mim" estiver selecionado, definir cookies
            if ($lembre_de_mim) {
                setcookie("id_usuario", $usuario['id'], time() + (86400 * 30), "/");
                setcookie("nome_usuario", $usuario['nome'], time() + (86400 * 30), "/");
                setcookie("tipo_usuario", $usuario['tipo'], time() + (86400 * 30), "/");
            }

            // Redirecionar para a página index
            header("Location: index.php");
            exit();
        } else {
            echo "Senha incorreta";
            exit();
        }
    } else {
        echo "Usuário não encontrado";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
