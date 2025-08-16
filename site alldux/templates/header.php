<?php
// templates/header.php
?>
<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="sortcut icon" href="img/logo.png" type="image/x-icon">
    <title>Rádio Canadá Brasil</title>
    <link rel="stylesheet" href="css/stylecomum.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
<body>

    <div class="tudo">
        <header class="header">
            <div class="logo">
                <img src="img/logo.png" alt="Ícone do site" class="icon">
                <h2>Radio canada Brasil</h2>
            </div>
            <?php
            // Lógica condicional para exibir o nome do usuário ou o botão de login
            if ($is_logged_in && !empty($nome_usuario)) {
                echo <<<USER_INFO
                <div class="nome">
                    <a href="perfil.php"><p>{$nome_usuario}</p></a>
                </div>
                <img src="{$foto_perfil}" alt="Foto de Perfil" style="width: 50px; height: 50px; border-radius: 50%;">
                USER_INFO;
            } else {
                echo '<a href="login.php" class="botao-login">Login</a>';
            }
            ?>
        </header>