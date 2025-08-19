<?php
// Este arquivo assume que $conn e a sessão já foram iniciados pelo index.php

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['id_usuario'];
$mensagem_erro = '';
$mensagem_sucesso = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';

// Se a página foi submetida via POST, processa a ação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ação: Atualizar nome e email
    if (isset($_POST['action']) && $_POST['action'] === 'update_data') {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);

        // NOVO: Verifica se o novo email já está em uso por OUTRO usuário
        $checkEmailSql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
        $checkStmt = $conn->prepare($checkEmailSql);
        $checkStmt->bind_param("si", $email, $usuarioId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $mensagem_erro = "Este email já está em uso por outra conta.";
        } else {
            // Se o email estiver livre, atualiza os dados
            $updateSql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ssi", $nome, $email, $usuarioId);
            if ($updateStmt->execute()) {
                $_SESSION['nome_usuario'] = $nome;
                header("Location: index.php?i=perfil&success=Dados atualizados com sucesso!");
                exit;
            } else {
                $mensagem_erro = "Erro ao atualizar os dados.";
            }
            $updateStmt->close();
        }
        $checkStmt->close();
    }

    // Ação: Mudar a senha
    if (isset($_POST['action']) && $_POST['action'] === 'update_password') {
        $senhaAtual = $_POST['senha_atual'];
        $novaSenha = $_POST['nova_senha'];
        $confirmarNovaSenha = $_POST['confirmar_nova_senha'];

        // NOVO: Busca a senha atual do usuário no banco
        $sqlSenha = "SELECT senha FROM usuarios WHERE id = ?";
        $stmtSenha = $conn->prepare($sqlSenha);
        $stmtSenha->bind_param("i", $usuarioId);
        $stmtSenha->execute();
        $resultSenha = $stmtSenha->get_result();
        $usuarioSenha = $resultSenha->fetch_assoc();
        $stmtSenha->close();

        // NOVO: Validações de senha
        if (!password_verify($senhaAtual, $usuarioSenha['senha'])) {
            $mensagem_erro = "A senha atual está incorreta.";
        } elseif ($novaSenha !== $confirmarNovaSenha) {
            $mensagem_erro = "As novas senhas não coincidem.";
        } elseif (strlen($novaSenha) < 6) {
            $mensagem_erro = "A nova senha deve ter no mínimo 6 caracteres.";
        } else {
            // Se tudo estiver certo, atualiza a senha
            $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $updateSenhaSql = "UPDATE usuarios SET senha = ? WHERE id = ?";
            $updateSenhaStmt = $conn->prepare($updateSenhaSql);
            $updateSenhaStmt->bind_param("si", $novaSenhaHash, $usuarioId);
            if ($updateSenhaStmt->execute()) {
                header("Location: index.php?i=perfil&success=Senha alterada com sucesso!");
                exit;
            } else {
                $mensagem_erro = "Erro ao alterar a senha.";
            }
            $updateSenhaStmt->close();
        }
    }

    // Ação: Excluir conta
    if (isset($_POST['action']) && $_POST['action'] === 'delete_account') {
        $deleteSql = "DELETE FROM usuarios WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $usuarioId);
        $deleteStmt->execute();
        $deleteStmt->close();
        
        session_destroy();
        header("Location: index.php?success=Conta excluída com sucesso");
        exit;
    }
}

// Busca os dados atuais do usuário para preencher os formulários
$sql = "SELECT nome, email FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();
?>

<div class="container2 perfil-container">
    <h3>Meu Perfil</h3>

    <?php if (!empty($mensagem_sucesso)): ?>
        <p class="form-message success"><?php echo $mensagem_sucesso; ?></p>
    <?php endif; ?>
    <?php if (!empty($mensagem_erro)): ?>
        <p class="form-message error"><?php echo $mensagem_erro; ?></p>
    <?php endif; ?>

    <h4>Atualizar Dados</h4>
    <form action="index.php?i=perfil" method="POST">
        <input type="hidden" name="action" value="update_data">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        
        <button type="submit">Atualizar Dados</button>
    </form>

    <hr>
    
    <h4>mudar tipo de conta</h4>

    <a href="escolher_tipo.php">mudar tipo</a>
    
    <hr>

    <h4>Alterar Senha</h4>
    <form action="index.php?i=perfil" method="POST">
        <input type="hidden" name="action" value="update_password">
        <label for="senha_atual">Senha Atual:</label>
        <input type="password" id="senha_atual" name="senha_atual" required>
        
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" id="nova_senha" name="nova_senha" required minlength="6" placeholder="Mínimo de 6 caracteres">
        
        <label for="confirmar_nova_senha">Confirmar Nova Senha:</label>
        <input type="password" id="confirmar_nova_senha" name="confirmar_nova_senha" required>
        
        <button type="submit">Alterar Senha</button>
    </form>

    <hr>

    <h4>Excluir Conta</h4>
    <form action="index.php?i=perfil" method="POST">
        <input type="hidden" name="action" value="delete_account">
        <p>Esta ação é permanente e não pode ser desfeita.</p>
        <button type="submit" class="btn-danger" onclick="return confirm('Tem certeza que deseja excluir sua conta permanentemente?');">Excluir Minha Conta</button>
    </form>
</div>