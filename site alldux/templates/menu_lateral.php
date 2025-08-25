<?php
// templates/menu_lateral.php
?>
<nav class="menu-lateral">
    <div class="btn-expandir">
        <i class="bi bi-list" id="btn-exp"></i>
    </div>
    <ul>
        <li class="item-menu ativo">
            <a href="?i=home.php">
                <span class="icon"><i class="bi bi-house-door"></i></span>
                <span class="txt-link">Home</span>
            </a>
        </li>
        <li class="item-menu">
            <a href="#">
                <button class="item-menu" onclick="redirecionaDashboard()">
                    <span class="icon"><i class="bi bi-columns-gap"></i></span>
                    <span class="txt-link">Dashboard</span>
                </button>
            </a>
        </li>
        <li class="item-menu">
            <?php
            if ($is_logged_in && !empty($nome_usuario)) {
                echo <<<USER_INFO
                <a href="index.php?i=perfil">
                    <span class="icon"><i class="bi bi-person-circle"></i></span>
                    <span class="txt-link">perfil</span>
                </a>
                USER_INFO;
            } else {
                echo '  
                <a href="criarconta.php">
                    <span class="icon"><i class="bi bi-person-circle"></i></span>
                    <span class="txt-link">Conta</span>
                </a>';
            }
            ?>
        </li>
        <li class="item-menu">
            <div class="auth-button">
                <?php
                // Lógica condicional para o botão de Login/Logout
                if ($is_logged_in) {
                    echo '<a href="logout.php" class="btn btn-danger">Logout</a>';
                } else {
                    echo '<a href="login.php" class="btn btn-primary">Login</a>';
                }
                ?>
            </div>
        </li>
        <li class="item-menu">
            <a href="#" onclick="abrirModalPedidos()">
                <span class="icon"><i class="bi bi-box-seam"></i></span>
                <span class="txt-link">Meus Pedidos</span>
            </a>
        </li>
    </ul>
</nav>