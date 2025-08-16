<?php

// 4. GERAÇÃO DO HTML

// Cabeçalho e abertura do body
include("processalogin.php");
echo <<<HTML_HEAD
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h1>Login</h1>
HTML_CONTENT;

if (isset($_GET['success'])) {
    echo "<div class='form-message success'>Conta criada com sucesso! Faça seu login.</div>";
}

echo <<<HTML_FORM
            <form action="" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <br>
                <input type="submit" value="Entrar">
            </form>
            <a href="criarconta.php" class="nav-link">Criar uma conta</a>
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

// 5. FECHAMENTO DA CONEXÃO
// Adicionado uma verificação para evitar o erro se a conexão falhar
if (isset($conn) && $conn) {
    $conn->close();
}
?>