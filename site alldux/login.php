<?php
// Inicia a sessão para poder lidar com futuras variáveis de sessão se necessário.
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/loginanimado.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styleespecifico.css">
</head>

<body class="dark-mode">
    <button class="theme-toggle" onclick="toggleTheme()">
        <i class="fas fa-moon" id="theme-icon"></i>
    </button>
    <div class="auth-container">
        <div class="image-container">
            <img src="img/escolhertipo.jpg" alt="Imagem lateral">
        </div>
        <div class="form-container">
            <h1>Login</h1>

            <?php
            // Verifica se há uma mensagem de erro vinda do processalogin.php
            if (isset($_GET['error'])) {
                echo "<div class='form-message error'>" . htmlspecialchars($_GET['error']) . "</div>";
            }
            // Verifica se há uma mensagem de sucesso vinda da página de criação de conta
            if (isset($_GET['success'])) {
                echo "<div class='form-message success'>Conta criada com sucesso! Faça seu login.</div>";
            }
            ?>

            <form action="processalogin.php" method="post">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="senha" placeholder="Senha" required>
                    <br>
                    <?php include 'templates/loginanimado.php' ?>
                </div>
            </form>
            <a href="criarconta.php" class="nav-link">Criar uma conta</a>
        </div>
    </div>

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