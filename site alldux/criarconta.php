<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
</head>

<body class="dark-mode">
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Corpo e tema escuro */
        body.dark-mode {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #121212;
            color: #e0e0e0;
        }

        body.light-mode {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #ffffffde;
            color: #333;
        }

        /* Container principal */
        .register-container {
            display: flex;
            width: 80%;
            border-radius: 8px;
        }

        /* Estilo da imagem na lateral esquerda */
        .image-container {
            width: 34%;
            z-index: 3; /* Mantendo a imagem acima do fundo */
        }

        .image-container img {
            margin-left: 15%;
            margin-top: auto;
            margin-bottom: auto;
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 50px;
        }

        /* Container do formulário */
        .form-container {
            border-radius: 50px;
            margin-right: auto;
            width: 60%;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            background-color: rgba(46, 46, 46, 0.9);
            z-index: 2; /* Manter o formulário acima da imagem */
        }

        body.light-mode .form-container {
            background-color: #f3e2d8;
        }

        /* Título do formulário */
        .form-container h1 {
            font-size: 1.8rem;
            color: inherit;
            margin-bottom: 1rem;
        }

        /* Campos de entrada */
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 65%;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border: 1px solid #333;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #333;
            color: #e0e0e0;
        }

        body.light-mode .form-container input[type="text"],
        body.light-mode .form-container input[type="email"],
        body.light-mode .form-container input[type="password"] {
            background-color: #f5f5f5;
            color: #333;
            border-color: #ccc;
        }

        /* Botão de envio */
        .form-container input[type="submit"] {
            width: 60%;
            padding: 0.8rem;
            margin-top: 1rem;
            border: none;
            border-radius: 5px;
            background-color: #1a73e8;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container input[type="submit"]:hover {
            background-color: #155ab2;
        }

        /* Links */
        .form-container .nav-link {
            color: #1a73e8;
            font-size: 0.9rem;
            text-decoration: none;
            margin-top: 1rem;
            display: inline-block;
        }

        .form-container .nav-link:hover {
            text-decoration: underline;
        }

        /* Botão de alternância de tema */
        .theme-toggle {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 8px 12px;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #1a73e8;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: #155ab2;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }

            .image-container {
                width: auto;
                height: auto;
                margin: auto;
            }

            .image-container img {
                width: 100%;
                margin: auto;
            }

            .form-container {
                width: 100%;
            }
        }
    </style>

    <!-- Botão para alternar tema -->
    <button class="theme-toggle" onclick="toggleTheme()">
        <i class="fas fa-sun" id="theme-icon"></i>
    </button>

    <div class="register-container">
        <!-- Contêiner da imagem de fundo -->
        <div class="image-container">
            <img src="img/paisagem-de-turismo2" alt="Imagem lateral"> <!-- Certifique-se de que a imagem esteja no caminho correto -->
        </div>

        <!-- Contêiner do formulário sobreposto -->
        <div class="form-container">
            <h1>Formulário de Cadastro</h1>
            <?php if (isset($_GET['error'])): ?>
                <p style="color: red;">Dados em uso por outra conta</p>
            <?php endif; ?>
            <form action="processa_usuario.php" method="post" enctype="multipart/form-data">
                <input type="text" name="nome" placeholder="Nome de Usuário" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <input type="submit" value="Cadastrar">
            </form>

            <a class="nav-link" href="login.php">Já possui uma conta? Faça Login</a>
        </div>
    </div>

    <script>
        // Função para alternar entre os temas
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
