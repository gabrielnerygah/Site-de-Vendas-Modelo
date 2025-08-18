<?php
// Inicia a sessão no topo do arquivo.
session_start();
include("conexao.php");

// --- LÓGICA DE PREPARAÇÃO ---
$is_logged_in = isset($_SESSION['id_usuario']);
$nome_usuario = isset($_SESSION['nome_usuario']) ? htmlspecialchars($_SESSION['nome_usuario']) : '';
$tipo_usuario = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : '';
$foto_perfil = isset($_SESSION['foto_perfil']) ? htmlspecialchars($_SESSION['foto_perfil']) : 'img/logo.png';

// --- ROTEAMENTO SEGURO ---
$pagina = 'home';
if (isset($_GET['i'])) {
    $pagina = $_GET['i']; 
}

// --- MONTAGEM DA PÁGINA ---
require_once 'templates/header.php';
require_once 'templates/menu_lateral.php';

// O switch agora também controla o acesso
switch ($pagina) {
    case 'home':
        include 'home.php';
        break;

    case 'dashboard_consumidor':
        // VERIFICA A PERMISSÃO AQUI, ANTES DE INCLUIR A PÁGINA
        if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'consumidor') {
            header("Location: login.php"); // Redireciona se não for consumidor
            exit;
        }
        include 'dashboard_consumidor.php';
        break;

    case 'dashboard_promotor':
         // VERIFICA A PERMISSÃO AQUI
        if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'promotor') {
            header("Location: login.php"); // Redireciona se não for promotor
            exit;
        }
        include 'dashboard_promotor.php';
        break;
        
    default:
        include 'home.php';
        break;
}

require_once 'templates/footer.php';
$conn->close();
?>