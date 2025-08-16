<?php
// Inicia a sessão no topo do arquivo.
session_start();

// --- LÓGICA DE PREPARAÇÃO ---
$is_logged_in = isset($_SESSION['usuario']);
$nome_usuario = isset($_SESSION['nome_usuario']) ? htmlspecialchars($_SESSION['nome_usuario']) : '';
$tipo_usuario = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : '';
$foto_perfil = isset($_SESSION['foto_perfil']) ? htmlspecialchars($_SESSION['foto_perfil']) : 'img/logo.png';


// --- ROTEAMENTO SEGURO ---
// Lista de páginas que podem ser carregadas
$paginas_permitidas = ['home', 'dashboard','dashboard_promotor', 'contato']; // Exemplo: adicione suas páginas aqui

// Define a página padrão
$pagina = 'home';

// Verifica se a página solicitada via GET está na lista de permissões
if (isset($_GET['i']) && in_array($_GET['i'], $paginas_permitidas)) {
    $pagina = $_GET['i'];
}


// --- MONTAGEM DA PÁGINA ---
// Carrega o cabeçalho (usando 'require_once' para garantir que ele exista)
require_once 'templates/header.php';
require_once 'templates/menu_lateral.php';

// Carrega o conteúdo da página solicitada
// O switch garante que apenas o arquivo correspondente à página seja incluído
switch ($pagina) {
    case 'home':
        include 'home.php';
        break;
    case 'dashboard':
		include 'dashboard_consumidor.php';
		break;
    case 'dashboard_promotor':
        include 'dashboard_promotor.php';
        break;
 
    default:
        include 'home.php';
        break;
}

// Carrega o rodapé
require_once 'templates/footer.php';
?>