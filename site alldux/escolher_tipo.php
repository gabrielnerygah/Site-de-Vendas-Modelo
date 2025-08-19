<?php
// 1. LÓGICA PHP NO TOPO
// A sessão DEVE ser a primeira coisa no arquivo.
session_start();
include('conexao.php');

// Apenas usuários logados podem escolher um tipo.
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

// Garante que a variável $tipo_usuario sempre exista para evitar erros
$tipo_usuario = $_SESSION['tipo_usuario'] ?? null;

// Prepara variáveis booleanas para deixar o HTML mais limpo
$isConsumidor = ($tipo_usuario === 'consumidor');
$isPromotor = ($tipo_usuario === 'promotor');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha seu Tipo de Conta</title>
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Reset e Estilos Gerais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        body.dark-mode {
            background: linear-gradient(135deg, #121212, #232526);
            color: #e0e0e0;
        }

        body.light-mode {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            color: #333;
        }
        
        /* Layout Principal */
        .auth-container {
            display: flex;
            align-items: center;
            width: 90%;
            max-width: 950px;
            position: relative;
        }

        .image-container {
            flex-basis: 45%;
            z-index: 2;
            box-shadow: 0 15px 30px rgba(0,0,0,0.25);
            border-radius: 20px;
        }

        .image-container img {
            width: 100%;
            display: block;
            border-radius: 20px;
        }

        /* Formulário com Efeito de Vidro */
        .form-container {
            flex-basis: 65%;
            padding: 2rem 2rem 2rem 8rem;
            margin-left: -120px;
            border-radius: 20px;
            z-index: 1;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            text-align: center;
        }

        body.dark-mode .form-container { background: rgba(46, 46, 46, 0.9); }
        body.light-mode .form-container { background: #f3e2d8 }

        .form-container h1 {
            font-size: 1.8;
            color: inherit;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        /* Formulário com as Opções */
        .type-selection-form {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
            align-items: center;
        }

        /* Estilo das opções clicáveis */
        .option {
            width: 200px;
            height: 250px;
            border-radius: 15px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .option img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .option p {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            background: #333;
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            margin: 0;
        }

        .option input[type="radio"] { display: none; }

        .option:hover {
            transform: scale(0.98);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 1);
        }

        .option:hover img { transform: scale(1.1); }

        .option.selected {
            border-color: #1a73e8;
            box-shadow: 0 0 10px rgba(0, 170, 255, 0.5);
        }
        /* Botão de Envio */
        .type-selection-form input[type="submit"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            background: #155ab2;
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .type-selection-form input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Botão de Tema */
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
            background: #333;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
        }
        body.light-mode .theme-toggle { background: #fff; color: #333; }

        /* Responsividade */
        @media (max-width: 768px) {
            body { align-items: flex-start; padding: 2rem 1rem; height: auto; }
            .auth-container { flex-direction: column; }
            .image-container { width: 60%; max-width: 250px; margin-bottom: -50px; }
            .form-container { width: 100%; margin-left: 0; padding: 4rem 1.5rem 1.5rem; }
            .option { width: 150px; height: 200px; }
        }
    </style>
</head>
<body class="dark-mode">

    <button class="theme-toggle" onclick="toggleTheme()">
        <i class="fas fa-sun" id="theme-icon"></i>
    </button>

    <div class="auth-container">
        <div class="image-container">
            <img src="img/escolhertipo.jpg" alt="Imagem lateral">
        </div>

        <div class="form-container">
            <h1>Escolha seu Tipo de Conta</h1>
            <form action="processa_tipo.php" method="post" class="type-selection-form">

                <label class="option <?php if ($isConsumidor) echo 'selected'; ?>">
                    <input type="radio" name="tipo" value="consumidor" <?php if ($isConsumidor) echo 'checked'; ?> required>
                    <img src="img/usuarios.jpg" alt="Consumidor">
                    <p>Consumidor</p>
                </label>

                <label class="option <?php if ($isPromotor) echo 'selected'; ?>">
                    <input type="radio" name="tipo" value="promotor" <?php if ($isPromotor) echo 'checked'; ?> required>
                    <img src="img/prestador de serviço.jpg" alt="Promotor">
                    <p>Produtor</p>
                </label>

                <input type="submit" value="Mudar Tipo de Conta">
            </form>
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
        // Garante que o ícone inicial esteja correto
        if (document.body.classList.contains('dark-mode')) {
            document.getElementById('theme-icon').classList.add('fa-moon');
        }
    </script>
</body>
</html>
<?php
// 5. FECHAMENTO DA CONEXÃO
$conn->close();
?>