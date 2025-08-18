<?php
session_start();
include('conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['id_usuario'];

// Buscar dados do usuário
$sql = "SELECT nome, email, tipo FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza os dados do usuário
    if (isset($_POST['nome']) && isset($_POST['email'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        $updateSql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssi", $nome, $email, $usuarioId);
        $updateStmt->execute();
        $updateStmt->close();

        // Atualiza a sessão
        $_SESSION['nome_usuario'] = $nome;
        header("Location: perfil.php?success=Dados atualizados com sucesso");
        exit;
    }

    // Mudar a senha
    if (isset($_POST['nova_senha'])) {
        $novaSenha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
        $updateSenhaSql = "UPDATE usuarios SET senha = ? WHERE id = ?";
        $updateSenhaStmt = $conn->prepare($updateSenhaSql);
        $updateSenhaStmt->bind_param("si", $novaSenha, $usuarioId);
        $updateSenhaStmt->execute();
        $updateSenhaStmt->close();
        header("Location: perfil.php?success=Senha alterada com sucesso");
        exit;
    }

    // Excluir conta
    if (isset($_POST['excluir_conta'])) {
        $deleteSql = "DELETE FROM usuarios WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $usuarioId);
        $deleteStmt->execute();
        $deleteStmt->close();
        session_destroy();
        header("Location: index.php?success=Conta excluída com sucesso");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt_BR">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="sortcut icon" href="img/logo.png" type="image/x-icon">
	<title>Rádio Canadá Brasil</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<a href=""></a>

<body>

<style>
    
    /* Reset básico */
* {
    width: 100%;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.tudo {
    width: 95%;
    margin-left: 70px;
}

@media (max-width: 768px) {
    .tudo {
        width: 85%;
        margin-left: 60px;
    }

}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    height: 100vh;
    padding: 10px;
}

/* Responsividade geral */
@media (max-width: 768px) {
    body {
        padding: 10px;
    }
}

/* Header */
.head {
    background-color: aqua;
}

.header {
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    background-color: #333;
    color: #fff;
}

.header .logo {
    display: flex;
    align-items: center;
}

.header .logo img.icon {
    width: 40px;
    height: 40px;
    margin-right: 10px;
}

.header h1 {
    font-size: 1.5rem;
    margin: 0;
}

@media (max-width: 768px) {
    .header {
        z-index: 1000;
        border-radius: 20px;
        flex-direction: column;
        text-align: center;
    }
}

/* Container */
.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #fdf3e1;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.box,
.box2 {
    flex: 1;
    margin: 10px;
    padding: 20px;
    border-radius: 8px;
    color: #4a4a4a;
    font-family: 'Roboto', sans-serif;
}

.box2 {
    background-color: #f9e9c3;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Responsividade do container */
@media (max-width: 768px) {
    .container {
        margin-left: 60px;
        flex-direction: column;
        padding: 5px;
        width: 88%;
    }

    .box,
    .box2 {

        margin-left: 60px 0;
    }
}

audio {
    width: 100%;
    appearance: none;
    /* Remove os estilos padrão */

}

audio::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 15px;
    height: 15px;
    background: #c53c1d;
    border-radius: 50%;
}

audio::-moz-range-thumb {
    background: #ff5733;
    border-radius: 50%;
}

@media (max-width: 768px) {
    audio {
        width: 70%;
        appearance: none;
        /* Remove os estilos padrão */

    }

    audio::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 15px;
        height: 15px;
        background: #c53c1d;
        border-radius: 50%;
    }

    audio::-moz-range-thumb {
        background: #ff5733;
        border-radius: 50%;
    }
}

/* WhatsApp button */
.whatsap {
    position: fixed;
    width: 60px;
    height: 60px;
    bottom: 40px;
    right: 40px;
    background-color: #25d366;
    color: #fff;
    border-radius: 50%;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 30px;
    box-shadow: 3px 3px #888;
    z-index: 100;
}

.whatsap a i {
    color: white;
}

/* Footer */
/* Estilos do Footer */
footer {
    background-color: #333;
    padding: 20px 0;
    color: #fff;
    text-align: center;
    font-family: 'Arial', sans-serif;
}

.footer-container {
    margin: 0;
}

.footer-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}


/* Redes sociais */
.social-links {
    width: 40%;
    display: flex; /* Garante que os itens fiquem em linha */
    justify-content: center; /* Centraliza os ícones */
    gap: 5px; /* Espaçamento entre os ícones */
    margin-top: 10px;
    flex-wrap: nowrap; /* Impede que os ícones quebrem linha */
}

.social-links a {
    text-decoration: none;
    color: #fff;
    font-size: 3rem;
    display: flex;
    align-items: center;
    transition: font-size 0.3s ease;
}

.social-links a i {
    font-size: 3rem;
    transition: color 0.3s ease, font-size 0.3s ease;
}

/* Responsividade */
@media (max-width: 768px) {
    .social-links {
        gap: 5px;
    }

    .social-links a i {
        font-size: 2.5rem; /* Reduz o tamanho dos ícones em telas menores */
    }
}

/* Efeito hover para cada rede social */

/* Facebook */
.social-links a:hover i.fa-facebook-f {
    color: #1877f2;
}

/* Twitter */
.social-links a:hover i.fa-twitter {
    color: #1da1f2;
}

/* Instagram */
.social-links a:hover i.fa-instagram {
    background: linear-gradient(45deg, #f7241d, #e6683c, #0eb129, #0780e4, #2f00b1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* LinkedIn */
.social-links a:hover i.fa-linkedin-in {
    color: #0a66c2;
}



/* Carrossel */
.carousel-container {
    width: 25%;
    /* Ajuste a largura conforme necessário */
    max-width: 800px;
    /* Tamanho máximo do carrossel */
    margin: 50px auto;
    /* Centraliza o carrossel */
    position: relative;
    overflow: hidden;
    /* Esconde partes do carrossel que estão fora da área visível */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .carousel-container {
        width: 90%;
    }
}

.carousel {
    display: flex;
    transition: transform 0.5s ease-in-out;
    /* Transição suave entre os slides */
}

.carousel-item {
    min-width: 100%;
    /* Cada item ocupa 100% da largura do carrossel */
    height: auto;
    /* A altura será proporcional à largura da imagem */
}

.carousel-item img {
    width: 100%;
    /* A imagem ocupa 100% da largura do item do carrossel */
    height: auto;
    /* Mantém a proporção da imagem */
    object-fit: cover;
    /* Cobre toda a área do container sem distorcer a proporção */
    border-radius: 10px;
    /* Bordas arredondadas para um visual mais moderno */
}

/* Estilos dos botões de controle */
.controls {
    position: absolute;
    top: 50%;
    width: 100%;
    display: flex;
    justify-content: space-between;
    transform: translateY(-50%);
}

.control-button {
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    border-radius: 10px;
    width: 10%;
    height: 50px;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
}

.control-button:hover {
    background-color: rgba(248, 247, 247, 0.8);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
}

.control-button:focus {
    outline: none;
}

/* Responsividade */
@media (max-width: 768px) {
    .carousel-container {
        margin: 20px;
        /* Reduz a margem em telas menores */
    }

    .control-button {
        font-size: 18px;
        /* Tamanho menor dos botões */
        padding: 8px;
        /* Menos espaço nos botões */
    }
}



/* Menu lateral */
nav.menu-lateral {
    z-index: 1000;
    width: 70px;
    /* Largura inicial */
    height: 100%;
    background-color: #302d2d;
    padding: 40px 0 40px 1%;
    position: fixed;
    top: 0;
    left: 0;
    overflow: hidden;
    transition: 0.3s;
    /* Transição suave para expandir */
}

nav.menu-lateral.expandir {
    width: 300px;
    /* Largura expandida */
}

.btn-expandir {
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-color: rgb(255, 44, 44);
    width: 100%;
    padding-left: 10px;
}

.btn-expandir>i {
    color: #fff;
    font-size: 24px;
    cursor: pointer;
}

ul {
    height: 100%;
    list-style-type: none;
}

ul li.item-menu {
    transition: 0.2s;
}

ul li.ativo {
    margin-top: 30px;
    margin-bottom: 30px;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    background-color: #3dcd00;
}

ul li.item-menu:hover {
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    background: #03a413;
}

ul li.item-menu a {
    color: #fff;
    text-decoration: none;
    font-size: 20px;
    padding: 20px 4%;
    display: flex;
    margin-bottom: 2%;
    line-height: 40px;
    border-top-left-radius: 20%;
    border-bottom-left-radius: 20%;
}

ul li.item-menu a button {
    background-color: transparent;
    color: #fff;
    text-decoration: none;
    font-size: 20px;
    display: flex;
    border: none;
    line-height: 40px;
    border-top-left-radius: 20%;
    border-bottom-left-radius: 20%;
}

ul li.item-menu a .txt-link {
    margin-left: 4%;
    transition: 0.5s;
    opacity: 0;
}

nav.menu-lateral.expandir .txt-link {
    margin-left: 40px;
    opacity: 1;
}

ul li.item-menu a .icon>i {
    font-size: 20px;
    margin-left: 10px;
}

/* Responsividade */
@media (max-width: 768px) {
    nav.menu-lateral {
        z-index: 100;
        width: 60px;
        /* Largura reduzida em telas menores */
    }

    nav.menu-lateral.expandir {
        width: 250px;
        /* Largura expandida para telas menores */
    }
}

/* Estilos gerais */
h1 {
    text-align: center;
    font-size: 2.5em;
    background: linear-gradient(to right, #7df25f, #21942a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

@media (max-width: 768px) {

    h1 {
        text-align: center;
        font-size: 30px;
        background: linear-gradient(to right, #7df25f, #21942a);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
}

h2 {
    margin-left: 20px;
}

p {
    color: #555;
    line-height: 1.6;
}

.highlight {
    color: #d9534f;
    font-weight: bold;
}

.link a {
    color: #0275d8;
    transition: color 0.3s;
}

.link a:hover {
    color: #d9534f;
}


/* Telas grandes (laptops e desktops acima de 1200px) */
@media (min-width: 1200px) {
    .tudo {
        width: 95%;
        margin-left: auto;
    }

    .container {
        width: 85%;
        margin: 20px auto;
        padding: 40px;
    }

    .box, .box2 {
        font-size: 1.2rem;
    }

    h1 {
        font-size: 7rem;
    }
}

/* Telas médias (entre 992px e 1199px) */
@media (min-width: 992px) and (max-width: 1199px) {
    .tudo {
        width: 92%;
        margin-left: auto;
    }

    .container {
        width: 80%;
        margin: 15px auto;
    }

    .box, .box2 {
        font-size: 1.1rem;
    }

    h1 {
        font-size: 5rem;
    }
}

/* Telas médias (entre 768px e 991px) */
@media (min-width: 768px) and (max-width: 991px) {
    .tudo {
        width: 90%;
        margin-left: auto;
    }

    .container {
        width: 80%;
        padding: 20px;
        margin-left: auto;
        margin-right: auto;
    }

    h1 {
        font-size: 4rem;
    }

    .carousel-container {
        width: 60%;
    }
}

/* Pequenos ajustes para telas menores (smartphones abaixo de 600px) */
@media (max-width: 600px) {
    .header {
        padding: 10px;
        flex-direction: column;
        text-align: center;
    }

    h1 {
        font-size: 1.8rem;
    }

    .container {
        padding: 10px;
    }

    .box, .box2 {
        font-size: 1rem;
        padding: 10px;
    }

    .carousel-container {
        width: 100%;
    }

    .whatsap {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }

    .social-links a i {
        font-size: 2rem;
    }
}

.auth-button p a{ 
    background-color:
    background-color: #e91414; /* Cor de fundo do botão */
    color: white; /* Cor do texto do botão */
    padding-bottom: 5px ;
    padding-top: 5px ;
    padding: 10px; /* Espaçamento interno */
    border: none; /* Remove bordas */
    border-radius: 4px; /* Bordas arredondadas */
    cursor: pointer; /* Muda o cursor ao passar sobre o botão */
    font-size: auto; /* Tamanho responsivo do botão */
    transition: background-color 0.3s; /* Transição suave para hover */
    text-decoration: none;
    margin-right: 2px;}

.auth-button a{
   background-color:#11c90b; /* Cor de fundo do botão */
  color: white; /* Cor do texto do botão */
  padding-bottom: 5px ;
  padding-top: 5px ;
  padding: 10px; /* Espaçamento interno */
  border: none; /* Remove bordas */
  border-radius: 10px; /* Bordas arredondadas */
  cursor: pointer; /* Muda o cursor ao passar sobre o botão */
  font-size: auto; /* Tamanho responsivo do botão */
  transition: background-color 0.3s; /* Transição suave para hover */
  text-decoration: none;
  margin-right: 2px;
}

.botao-login{
     max-width: 60px;
    background-color:#11c90b; /* Cor de fundo do botão */
  color: white; /* Cor do texto do botão */
  padding-bottom: 5px ;
  padding-top: 5px ;
  padding: 10px; /* Espaçamento interno */
  border: none; /* Remove bordas */
  border-radius: 4px; /* Bordas arredondadas */
  cursor: pointer; /* Muda o cursor ao passar sobre o botão */
  font-size: auto; /* Tamanho responsivo do botão */
  transition: background-color 0.3s; /* Transição suave para hover */
  text-decoration: none;
  margin-right: 2px;
}

.nome {
    text-decoration: none ;
    width: 30%;
    padding: auto;
    font-family: 'Arial', sans-serif; /* Fonte moderna */
    font-size: 24px; /* Tamanho da fonte */
    color: #ffffff; /* Cor do texto */
    text-align: center; /* Centraliza o texto */
    margin-top: 10px; /* Espaçamento acima */
    margin-bottom: 5px; /* Espaçamento abaixo */
}
.nome a{
    text-decoration: none ;
    width: 30%;
    padding: auto;
    font-family: 'Arial', sans-serif; /* Fonte moderna */
    font-size: 24px; /* Tamanho da fonte */
    color: #ffffff; /* Cor do texto */
    text-align: center; /* Centraliza o texto */
    margin-top: 10px; /* Espaçamento acima */
    margin-bottom: 5px; /* Espaçamento abaixo */
}
/* Efeito ao passar o mouse */
.nome:hover { 
    border-radius: 50px;
    background-color: #e0e0e0; /* Cor do fundo ao passar o mouse */
    cursor: pointer; /* Muda o cursor ao passar sobre o nome */
}

/* estilo não padrão*/
/**/
/**/
/**/
/**/
/**/
.container2 {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.h2 {
    margin-bottom: 20px;
    color: #333;
}

form {
    margin-bottom: 30px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 8px;
    margin-bottom: 15px;
    transition: border 0.3s;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
}

button {
    background-color: #007bff;
    color: #ffffff;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0056b3;
}

.success {
    color: green;
    margin-bottom: 20px;
    font-weight: bold;
}


</style>


	<nav class="menu-lateral">

		<div class="btn-expandir">
			<i class="bi bi-list" id="btn-exp"></i>
		</div><!--btn-expandir-->

		<ul>
			<li class="item-menu ativo">
				<a href="index.php">
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
				<a href="criarconta.php">
					<span class="icon"><i class="bi bi-person-circle"></i></span>
					<span class="txt-link">Conta</span>
				</a>
			</li>
			<li class="item-menu">
			<div class="auth-button">
		<?php if (isset($_SESSION['usuario'])): ?>
			<!-- Botão de Logout -->
			<p><a href="logout.php" class="btn btn-danger">Logout</a></p>
		<?php else: ?>
			<a href="login.php" class="btn btn-primary">Login</a>
		<?php endif; ?>
	</div>
	</li>
		</ul>

	</nav>
	<div class="tudo">
		<header class="header">
			<div class="logo">
				<img src="img/logo.png" alt="Ícone do site" class="icon">
				<h2>Radio canada Brasil</h2>
			</div>
    <?php if (isset($_SESSION['nome_usuario'])): ?>
    <div class="nome">
         <a href="perfil.php"><p><?= htmlspecialchars($_SESSION['nome_usuario']) ?></p></a>
        </div><img src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="">
    <?php else: ?>
         <a href="login.php" class="botao-login">Login</a>
    <?php endif; ?>

</div>
		</header>

		<h1>Rádio Canadá Brasil</h1>
		<div class="container">
            <div class="container2">
                <h2 class="h2">Perfil do Usuário</h2>
        
                <?php if (isset($_GET['success'])): ?>
                    <p class="success"><?php echo htmlspecialchars($_GET['success']); ?></p>
                <?php endif; ?>
        
                <form method="POST">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
        
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        
                    <button type="submit">Atualizar Dados</button>
                </form>
        
                <form method="POST">
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" name="nova_senha" required>
        
                    <button type="submit">Alterar Senha</button>
                </form>
        
                <form method="POST">
                    <button type="submit" name="excluir_conta" onclick="return confirm('Tem certeza que deseja excluir sua conta?');">Excluir Conta</button>
                </form>
            </div>

		</div>
	</div>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

	<div class="whatsap">
		<a href="https://wa.me/5585981252196?text=">
			<i class="fa fa-whatsapp"></i>
		</a>
	</div>

	<footer>
		<div class="footer-container">
			<div class="footer-content">
				<p>&copy; 2024 Alldux. Todos os direitos reservados.</p>
				<p>&copy; Made by Gabriel de Morais Lacerda</p>
				<p>TEL: +55 85 981252196</p>
				<ul class="social-links">
					<li><a href="https://www.facebook.com/share/p/1CHiibDbBs/"><i class="fab fa-facebook-f"></i></a></li>
					<li><a href="#"><i class="fab fa-twitter"></i></a></li>
					<li><a href="https://www.instagram.com/allduxguide"><i class="fab fa-instagram"></i></a></li>
					<li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
				</ul>
			</div>
		</div>
	</footer>
</body>



<script>

function redirecionaDashboard() {
    <?php if ($_SESSION['tipo_usuario'] == 'consumidor') { ?>
        window.location.href = 'dashboard_consumidor.php';
    <?php } else if ($_SESSION['tipo_usuario'] == 'promotor') { ?>
        window.location.href = 'dashboard_promotor.php';
    <?php } else { ?>
        alert("Tipo de usuário inválido.");
    <?php } ?>
}

	//Seleciona os itens clicado
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

	//Expandir o menu

	var btnExp = document.querySelector('#btn-exp')
	var menuSide = document.querySelector('.menu-lateral')

	btnExp.addEventListener('click', function () {
		menuSide.classList.toggle('expandir')
	})


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

	function updateCarousel() {
		const offset = -currentIndex * 100; // Calcula o deslocamento com base no índice atual
		carousel.style.transform = `translateX(${offset}%)`; // Move o carrossel para o slide certo
	}
</script>

</html>