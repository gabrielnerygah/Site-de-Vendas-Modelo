<?php
// Ativa a exibição de erros para facilitar o debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão
session_start();

// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');

// Verifica se o usuário está logado e é do tipo consumidor
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'consumidor') {
    header("Location: login.php"); // Redireciona para login se não for consumidor
    exit;
}

// Consulta todos os produtos e o nome do promotor relacionado
$sql = "SELECT produtos.id, produtos.nome, produtos.descricao, produtos.preco, produtos.imagem, usuarios.nome AS promotor_nome 
        FROM produtos
        JOIN usuarios ON produtos.usuario_id = usuarios.id";
$result = $conn->query($sql);

// Verifica se houve erro na consulta
if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!-- HTML e CSS para layout da página, menu lateral, header, produtos, carrossel, footer, etc. -->

<!-- Menu lateral com navegação -->
<nav class="menu-lateral">
    <!-- Botão para expandir o menu -->
    <div class="btn-expandir">
        <i class="bi bi-list" id="btn-exp"></i>
    </div>
    <ul>
        <!-- Item do menu: Home -->
        <li class="item-menu ativo">
            <a href="index.php">
                <span class="icon"><i class="bi bi-house-door"></i></span>
                <span class="txt-link">Home</span>
            </a>
        </li>
        <!-- Item do menu: Dashboard, redireciona conforme tipo de usuário -->
        <li class="item-menu">
            <a href="#">
                <button class="item-menu" onclick="redirecionaDashboard()">
                    <span class="icon"><i class="bi bi-columns-gap"></i></span>
                    <span class="txt-link">Dashboard</span>
                </button>
            </a>
        </li>
        <!-- Item do menu: Conta -->
        <li class="item-menu">
            <a href="criarconta.php">
                <span class="icon"><i class="bi bi-person-circle"></i></span>
                <span class="txt-link">Conta</span>
            </a>
        </li>
        <!-- Item do menu: Login/Logout dinâmico -->
        <li class="item-menu">
            <div class="auth-button">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Login</a>
                <?php endif; ?>
            </div>
        </li>
    </ul>
</nav>

<!-- Header com logo e nome do usuário logado -->
<header class="header">
    <div class="logo">
        <img src="img/logo.png" alt="Ícone do site" class="icon">
        <h2>Radio canada Brasil</h2>
    </div>
    <?php if (isset($_SESSION['nome_usuario'])): ?>
        <div class="nome">
            <a href="perfil.php"><p><?= htmlspecialchars($_SESSION['nome_usuario']) ?></p></a>
        </div>
        <img src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="">
    <?php else: ?>
        <a href="login.php" class="botao-login">Login</a>
    <?php endif; ?>
</header>

<!-- Título principal -->
<h1>Rádio Canadá Brasil</h1>

<!-- Container principal dos produtos -->
<div class="container">
    <div class="container2">
        <h3>Produtos Disponíveis</h3>
        <ul class="ul">
            <!-- Loop para exibir cada produto -->
            <?php while ($produto = $result->fetch_assoc()): ?>
                <li class="il">
                    <a href="produto.php?id=<?php echo $produto['id']; ?>">
                        <strong>
                            <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>"><br>
                            <div class="descricao">
                                <?php echo $produto['nome']; ?>
                                <?php echo $produto['descricao']; ?><br>
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?><br>
                                Vendido por: <?php echo $produto['promotor_nome']; ?>
                            </div>
                        </strong>
                    </a><br>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<!-- Carrossel de anúncios -->
<div class="carousel-container">
    <div class="carousel">
        <div class="carousel-item">
            <img src="img/VISITE.png" alt="Anúncio 1">
        </div>
        <div class="carousel-item">
            <img src="img/kumon.jpg" alt="Anúncio 2">
        </div>
        <div class="carousel-item">
            <img src="anuncio3.jpg" alt="Anúncio 3">
        </div>
        <!-- Adicione mais itens de anúncio conforme necessário -->
    </div>
    <div class="controls">
        <button id="prev" class="control-button">❮</button>
        <button id="next" class="control-button">❯</button>
    </div>
</div>

<!-- Botão flutuante do WhatsApp -->
<div class="whatsap">
    <a href="https://wa.me/5585981252196?text=">
        <i class="fa fa-whatsapp"></i>
    </a>
</div>

<!-- Footer com informações de contato e redes sociais -->
<footer>
    <div class="footer-container">
        <div class="footer-content">
            <p>&copy; 2024 Alldux. Todos os direitos reservados.</p>
            <p>&copy; Made by Gabriel de Morais Lacerda</p>
            <p>TEL: +55 85 981258196</p>
            <ul class="social-links">
                <li><a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
            </ul>
        </div>
    </div>
</footer>

<!-- Scripts JS para menu lateral, carrossel e redirecionamento -->
<script>
// Função para redirecionar para o dashboard correto conforme tipo de usuário
function redirecionaDashboard() {
if ("<?php echo $_SESSION['tipo_usuario']; ?>" === "consumidor") {
    window.location.href = 'dashboard_consumidor.php';
} else if ("<?php echo $_SESSION['tipo_usuario']; ?>" === "promotor") {
    window.location.href = 'dashboard_promotor.php';
} else {
    alert("Você não está logado ou o tipo de usuário é inválido.");
    window.location.href = 'login.php';
}
}

// Seleciona os itens do menu e adiciona classe 'ativo' ao clicar
var menuItem = document.querySelectorAll('.item-menu')
function selectLink() {
    menuItem.forEach((item) =>
        item.classList.remove('ativo')
    )
    this.classList.add('ativo')
}
menuItem.forEach((item) =>
    item.addEventListener('click', selectLink)
)

// Expande ou recolhe o menu lateral ao clicar no botão
var btnExp = document.querySelector('#btn-exp')
var menuSide = document.querySelector('.menu-lateral')
btnExp.addEventListener('click', function () {
    menuSide.classList.toggle('expandir')
})

// Carrossel de anúncios: navegação entre slides
const carousel = document.querySelector('.carousel');
const items = document.querySelectorAll('.carousel-item');
const totalItems = items.length;
let currentIndex = 0;

document.getElementById('next').addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % totalItems; // Avança para o próximo slide
    updateCarousel();
});
document.getElementById('prev').addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + totalItems) % totalItems; // Volta para o slide anterior
    updateCarousel();
});

// Atualiza a posição do carrossel conforme o índice atual
function updateCarousel() {
    const offset = -currentIndex * 100;
    carousel.style.transform = `translateX(${offset}%)`;
}
</script>
