<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>escolher tipo de conta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="dark-mode">
    <style>
        :root {
         --altura-opcao: 50%; /* Defina a altura desejada */
         --altura-margin:2rem;
         }

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
            margin-left: 10%;
            margin-top: --altura-margin;
            margin-bottom: 0;
            width: 100%;
            height: --altura-opcao ;
            object-fit: cover;
            border-radius: 50px;
        }

        /* Container do formulário */


        body.light-mode .form-container {
            background-color: #f3e2d8;
        }

        /* Título do formulário */
        .form-container h1 {
            font-size: 1.8rem;
            color: inherit;
            margin-bottom: 1rem;
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

        h1 {
            color: inherit;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 600;
        }

        /* Container do formulário */
        .form-container {
            height: --altura-opcao;
            border-radius: 50px;
            display: flex;
            justify-content: center;
            width: auto;
            padding-top: --altura-margin ;
            padding-left: 2rem;
            padding-right: 2rem;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 30px;
            text-align: center;
            background-color: rgba(46, 46, 46, 0.9);
            z-index: 2; /* Manter o formulário acima da imagem */
        }
        .form-container2 {
            border-radius: 50px;
            display: flex;
            justify-content: center;
            width: auto;
            padding: 2rem;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 30px;
            text-align: center;
            background-color: rgba(63, 62, 62, 0.945);
            z-index: 2; /* Manter o formulário acima da imagem */
        }


        /* Estilo das opções */
        .option {
            width: 220px;
            height: 220px;
            border-radius: 10px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .option img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            object-fit: cover;
        }

        .option input {
            display: none; /* Oculta os botões de rádio */
        }

        /* Efeito hover e seleção */
        .option:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            border-color: #007BFF;
        }

        .option input:checked + img {
            border: 5px solid #007BFF;
        }

        /* Estilo do botão de submit */
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 220px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
         /* Responsividade */
         @media (max-width: 768px) {
            .form-container {
                flex-direction: column;
            }

            .option {
                width: 180px;
                height: 180px;
            }

            input[type="submit"] {
                max-width: 90%;
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
            <img src="img/escolhertipo.jpg" alt="Imagem lateral">
        </div>

        <!-- Contêiner do formulário sobreposto -->
        <div class="form-container">
            <div>
                <h1>Escolha seu Tipo de Conta</h1>
                <form action="processa_tipo.php" method="post" class="form-container2">
                    <!-- Botão para Consumidor -->
                    <label class="option">
                        <input type="radio" name="tipo" value="consumidor" required>
                        <img src="img/usuarios.jpg" alt="Consumidor">
                        <p>Consumidor</p>
                    </label>
        
                    <!-- Botão para Promotor -->
                    <label class="option">
                        <input type="radio" name="tipo" value="promotor" required>
                        <img src="img/prestador de serviço.jpg" alt="Promotor">
                        <p>Prestador de Serviço/p>
                    </label>
        
                    <!-- Botão Continuar abaixo das imagens -->
                    <input type="submit" value="Continuar">
                </form>
            </div>
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