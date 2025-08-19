<?php
// Inicia a sessão no topo do arquivo.
session_start();
include "conexao.php";

// --- LÓGICA DE PREPARAÇÃO ---
$is_logged_in = isset($_SESSION['id_usuario']);
$nome_usuario = isset($_SESSION['nome_usuario']) ? htmlspecialchars($_SESSION['nome_usuario']) : '';
$tipo_usuario = $_SESSION['tipo_usuario'] ?? '';
$foto_perfil = isset($_SESSION['foto_perfil']) ? htmlspecialchars($_SESSION['foto_perfil']) : 'img/logo.png';

// --- ROTEAMENTO SEGURO ---
$pagina = 'home';
if (isset($_GET['i'])) {
    $pagina = $_GET['i'];
}

// --- MONTAGEM DA PÁGINA ---
require_once 'templates/header.php';
require_once 'templates/menu_lateral.php';

echo '<div class="tudo">'; // Abre o container principal

// O switch agora também controla o acesso
switch ($pagina) {
    case 'home':
        include 'home.php';
        break;

    case 'dashboard_consumidor':
        if (!$is_logged_in || $tipo_usuario !== 'consumidor') {
            header("Location: login.php");
            exit;
        }
        include 'dashboard_consumidor.php';
        break;

    case 'dashboard_promotor':
        if (!$is_logged_in || $tipo_usuario !== 'promotor') {
            header("Location: login.php");
            exit;
        }
        include 'dashboard_promotor.php';
        break;
        
    case 'perfil':
        // ADICIONADO: Apenas usuários logados podem ver o perfil.
        if (!$is_logged_in) {
            header("Location: login.php");
            exit;
        }
        include 'perfil.php';
        break;

    default:
        include 'home.php';
        break;
}

echo '</div>'; // Fecha o container principal

require_once 'templates/footer.php';
$conn->close();
?>