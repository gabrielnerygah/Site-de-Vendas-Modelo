<?php
// 1. INICIALIZAÇÃO E PROCESSAMENTO DO FORMULÁRIO
session_start();


// Conexão com o banco de dados 
 include('conexao.php');
// Certifique-se de que o caminho está correto

$mensagem_erro = '';
$mensagem_sucesso = '';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e limpa os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $caminho_foto_perfil = 'img/default-avatar.png'; // Foto padrão

    // Validação básica dos campos
    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem_erro = "Todos os campos são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem_erro = "O formato do email é inválido.";
    } else {
        // Verifica se o email ou nome de usuário já existem
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR nome = ?");
        $stmt->bind_param("ss", $email, $nome);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $mensagem_erro = "Email ou nome de usuário já está em uso.";
        } else {
            // Processamento do upload da foto de perfil
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $diretorio_uploads = 'uploads/perfil/';
                // Cria um nome de arquivo único para evitar substituições
                $nome_arquivo = uniqid() . '_' . basename($_FILES['foto']['name']);
                $caminho_completo = $diretorio_uploads . $nome_arquivo;

                // Cria o diretório se ele não existir
                if (!is_dir($diretorio_uploads)) {
                    mkdir($diretorio_uploads, 0777, true);
                }

                // Move o arquivo para o diretório de uploads
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_completo)) {
                    $caminho_foto_perfil = $caminho_completo;
                } else {
                    $mensagem_erro = "Houve um erro ao enviar sua foto.";
                }
            }

            // Se não houve erro no upload, continua com o cadastro
            if (empty($mensagem_erro)) {
                // CRIPTOGRAFA A SENHA - NUNCA SALVE SENHAS EM TEXTO PURO!
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                // Insere o novo usuário no banco de dados
                $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, foto_perfil) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nome, $email, $senha_hash, $caminho_foto_perfil);

                if ($stmt->execute()) {
                    // Redireciona para a página de login com uma mensagem de sucesso
                    header("Location: login.php?success=1");
                    exit();
                } else {
                    $mensagem_erro = "Erro ao criar a conta. Tente novamente.";
                }
            }
        }
        $stmt->close();
    }
}

// Prepara as mensagens para serem exibidas no HTML


// 2. GERAÇÃO DO HTML

// Cabeçalho e abertura do body
echo <<<HTML_HEAD
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styleespecifico.css"> 
</head>
<body class="dark-mode">
HTML_HEAD;

// Conteúdo principal da página
echo <<<HTML_CONTENT
<button class="theme-toggle" onclick="toggleTheme()">
    <i class="fas fa-moon" id="theme-icon"></i>
</button>
<div class="auth-container">
    <div class="image-container">
        <img src="img/escolhertipo.jpg" alt="Imagem lateral">
    </div>
    <div class="form-container">
        <h1>Formulário de Cadastro</h1>
HTML_CONTENT;

// Exibe a mensagem de erro, se houver
if (!empty($mensagem_erro)) {
    echo "<div class='form-message error'>{$mensagem_erro}</div>";
}

echo <<<HTML_FORM
<form action="" method="post" enctype="multipart/form-data">
    <input type="text" name="nome" placeholder="Nome de Usuário" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    
    <label for="foto" style="margin-top: 10px; font-size: 0.9em;"></label>
    <input type="file" name="foto" id="foto" accept="image/png, image/jpeg, image/gif" style="margin: 5px auto; width: 65%;">
    
    <input type="submit" value="Cadastrar">
</form>
<a href="login.php" class="nav-link">Já possui uma conta? Faça Login</a>
</div>
</div>
HTML_FORM;

// Script e fechamento do HTML
echo <<<HTML_SCRIPT
    <script>
        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            document.body.classList.toggle('light-mode');
            const themeIcon = document.getElementById('theme-icon');
            if (document.body.classList.contains('dark-mode')) {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        }
    </script>
</body>
</html>
HTML_SCRIPT;

// Fechamento da conexão
if (isset($conn) && $conn) {
    $conn->close();
}
?>