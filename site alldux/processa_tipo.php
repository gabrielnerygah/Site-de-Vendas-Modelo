<?php
// Inicia a sessão
session_start();
include('conexao.php');

// 1. VERIFICAÇÃO DE SEGURANÇA
// Apenas usuários logados podem mudar seu tipo
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o formulário foi enviado com o método POST e se o campo 'tipo' existe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo'])) {
    
    $novo_tipo = $_POST['tipo'];
    $usuarioId = $_SESSION['id_usuario'];
    
    // 2. VALIDAÇÃO
    // Garante que o valor enviado seja um dos permitidos
    if ($novo_tipo === 'consumidor' || $novo_tipo === 'promotor') {
        
        // 3. ATUALIZAÇÃO NO BANCO DE DADOS
        // Usa prepared statements para máxima segurança
        $sql = "UPDATE usuarios SET tipo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $novo_tipo, $usuarioId);
        
        if ($stmt->execute()) {
            // 4. ATUALIZAÇÃO NA SESSÃO
            // Se a atualização no banco deu certo, atualiza a sessão também
            $_SESSION['tipo_usuario'] = $novo_tipo;
            
            // 5. REDIRECIONAMENTO
            // Redireciona de volta para a página de perfil com mensagem de sucesso
            header("Location: index.php?i=perfil&success=Tipo de conta alterado com sucesso!");
            exit;
        } else {
            // Se deu erro na atualização
            header("Location: index.php?i=escolher_tipo&error=Erro ao atualizar o tipo de conta.");
            exit;
        }
        $stmt->close();
    } else {
        // Se o valor enviado em 'tipo' não for válido
        header("Location: index.php?i=escolher_tipo&error=Tipo de conta inválido.");
        exit;
    }
} else {
    // Se o arquivo for acessado diretamente, redireciona para a home
    header("Location: index.php");
    exit;
}

$conn->close();
?>