
<?php
session_start();
include('conexao.php'); // Certifique-se de que este arquivo contém a conexão ao banco de dados.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $lembre_de_mim = isset($_POST['lembre_de_mim']);

    // Buscar o usuário no banco de dados
    $stmt = $conn->prepare("SELECT id, nome, tipo, senha FROM usuarios WHERE email = ?");
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
            $_SESSION['tipo'] = $usuario['tipo']; // Armazenando o tipo de usuário

            // Se "Lembre de mim" estiver selecionado, definir cookies
            if ($lembre_de_mim) {
                setcookie("id_usuario", $usuario['id'], time() + (86400 * 30), "/");
                setcookie("nome_usuario", $usuario['nome'], time() + (86400 * 30), "/");
                setcookie("tipo", $usuario['tipo'], time() + (86400 * 30), "/"); // Armazenando o tipo em cookie
            }

            // Redirecionar para a página inicial
            header("Location: index.php");
            exit();
        } else {
            // Senha incorreta
            header("Location: login.php?error=Senha incorreta");
            exit();
        }
    } else {
        // Usuário não encontrado
        header("Location: login.php?error=Usuário não encontrado");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .login-container {
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
            .login-container {
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

    <div class="login-container">
        <!-- Contêiner da imagem de fundo -->
        <div class="image-container">
            <img src="img/paisagem-de-turismo1" alt="Imagem lateral">
        </div>

        <!-- Contêiner do formulário sobreposto -->
        <div class="form-container">
            <h1>Login</h1>
            <?php if (isset($_GET['error'])): ?>
            <p style="color: red;">
                <?= htmlspecialchars($_GET['error']) ?>
            </p>
            <?php endif; ?>
            <form action="processalogin.php" method="post">
                <input type="text" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <br>
                <label>
                    <input type="checkbox" name="lembre_de_mim"> Lembre de mim
                </label>
                <br>
                <input type="submit" value="Entrar">
            </form>
            <a href="criarconta.php" class="nav-link">Criar uma conta</a>
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
