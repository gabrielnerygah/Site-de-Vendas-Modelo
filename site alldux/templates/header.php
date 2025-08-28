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
    <link rel="stylesheet" href="css/parallax.css">
    <?php if ($pagina == 'dashboard_consumidor'): ?>
        <link rel="stylesheet" href="css/dashboard_consumidor.css">
    <?php elseif ($pagina == 'perfil'): ?>
        <link rel="stylesheet" href="css/perfil.css">
    <?php endif; ?>
    <?php if ($pagina == 'dashboard_promotor'): ?>
        <link rel="stylesheet" href="css/dashboard_promotor.css">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:900" rel="stylesheet">
</head>



<body>

    <div class="tudo">
        <header class="header">
            <div class="logo">
                <div class="icon">
                    <?php include 'templates/icone.php'; ?>
                </div>
                <div class="concept concept-two">
                    <div class="hover">
                        <h1>F</h1>
                    </div>
                    <div class="hover">
                        <h1>O</h1>
                    </div>
                    <div class="hover">
                        <h1>R</h1>
                    </div>
                    <div class="hover">
                        <h1>E</h1>
                    </div>
                    <div class="hover">
                        <h1>S</h1>
                    </div>
                    <div class="hover">
                        <h1>T</h1>
                    </div>
                </div>
            </div>
            <a href="#" class="cart-icon-link" onclick="abrirModalCarrinho()">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count-badge" class="cart-badge" data-count="<?= $contagem_carrinho ?>">
                    <?= $contagem_carrinho ?>
                </span>
            </a>
        </header>