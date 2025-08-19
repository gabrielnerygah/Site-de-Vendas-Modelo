<?php


if (isset($_POST['adicionar_produto'])) {
    // Pega o ID do usuário que está logado na sessão
    $id_usuario_logado = $_SESSION['id_usuario'];

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $caminho_imagem = '';

    // Lógica para upload da imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $diretorio_uploads = 'uploads/'; // A pasta que você criou no passo anterior
        
        // Cria um nome de arquivo único para segurança
        $nome_arquivo = uniqid() . '_' . basename($_FILES['imagem']['name']);
        $caminho_completo = $diretorio_uploads . $nome_arquivo;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
            $caminho_imagem = $caminho_completo;
        }
    }

    // --- CORREÇÃO PRINCIPAL AQUI ---
    // A query INSERT agora inclui a coluna 'id_usuario'
    $stmt = $conn->prepare("INSERT INTO produtos (id_usuario, nome, descricao, preco, imagem) VALUES (?, ?, ?, ?, ?)");
    
    // O 'i' no início corresponde ao tipo de dado de id_usuario (integer)
    $stmt->bind_param("isdds", $id_usuario_logado, $nome, $descricao, $preco, $caminho_imagem);
    
    $stmt->execute();
    $stmt->close();

    // Redireciona para a mesma página para evitar reenvio do formulário
    header("Location: " . $_SERVER['PHP_SELF']); // Ou para index.php?i=dashboard_promotor
    exit();
}

// Busca todos os produtos para exibir na página
$sql = "SELECT * FROM produtos ORDER BY id DESC";
$result = $conn->query($sql);

// Prepara variáveis da sessão para exibição segura
$is_logged_in = isset($_SESSION['nome_usuario']);
$nome_usuario = htmlspecialchars($_SESSION['nome_usuario'] ?? '');
$foto_perfil = htmlspecialchars($_SESSION['foto_perfil'] ?? 'img/default-avatar.png'); // Usando uma foto padrão
$tipo_usuario = $_SESSION['tipo_usuario'] ?? '';


// 2. GERAÇÃO DO HTML DA PÁGINA

require_once 'templates/header.php';
require_once 'templates/menu_lateral.php';


// Conteúdo da Página de Produtos
echo <<<HTML_PRODUCTS
    <h1>Rádio Canadá Brasil</h1>
    <div class="container">
        <div class="container2">
            <h3>Adicionar Produto</h3>
            <form method="POST" enctype="multipart/form-data" class="add-product-form">
                <input type="text" name="nome" placeholder="Nome do Produto" required><br>
                <textarea name="descricao" placeholder="Descrição do Produto"></textarea><br>
                <input type="number" name="preco" step="0.01" placeholder="Preço" required><br>
                <input type="file" name="imagem" class="input-file-bonito"><br>
                <button type="submit" name="adicionar_produto">Adicionar Produto</button>
            </form>

            <h3>Produtos</h3>
            <div class="product-list">
HTML_PRODUCTS;

// Loop para exibir os produtos buscados do banco
if ($result->num_rows > 0) {
    while ($produto = $result->fetch_assoc()) {
        $imagem_path = htmlspecialchars($produto['imagem'] ?: 'default.png');
        $nome_produto = htmlspecialchars($produto['nome']);
        $descricao_produto = htmlspecialchars($produto['descricao']);
        $preco_formatado = number_format($produto['preco'], 2, ',', '.');
        
        echo <<<PRODUCT_CARD
            <div class="product">
                <img src="{$imagem_path}" alt="Imagem do Produto">
                <div class="product-info">
                    <strong>{$nome_produto}</strong><br>
                    <span>{$descricao_produto}</span><br>
                    <span>R$ {$preco_formatado}</span><br>
                </div>
            </div>
PRODUCT_CARD;
    }
} else {
    echo "<p>Nenhum produto cadastrado.</p>";
}

echo '</div></div></div>'; // Fechamento de .product-list, .container2, .container


require_once 'templates/footer.php';

// Bloco de JavaScript
echo "<script>";

echo "function redirecionaDashboard() {";
if ($tipo_usuario == 'consumidor') {
    echo "window.location.href = 'dashboard_consumidor.php';";
} else if ($tipo_usuario == 'promotor') {
    echo "window.location.href = 'dashboard_promotor.php';";
} else if ($is_logged_in) {
    echo "alert('Tipo de usuário inválido.');";
} else {
    echo "window.location.href = 'login.php';"; // Redireciona para login se não estiver logado
}
echo "}";

// O restante do JavaScript é estático
echo <<<'STATIC_JS'
    var menuItem = document.querySelectorAll('.item-menu');
    function selectLink() {
        menuItem.forEach((item) => item.classList.remove('ativo'));
        this.classList.add('ativo');
    }
    menuItem.forEach((item) => item.addEventListener('click', selectLink));

    var btnExp = document.querySelector('#btn-exp');
    var menuSide = document.querySelector('.menu-lateral');
    btnExp.addEventListener('click', function () {
        menuSide.classList.toggle('expandir');
    });

    const carousel = document.querySelector('.carousel');
    const items = document.querySelectorAll('.carousel-item');
    if (carousel && items.length > 0) {
        const totalItems = items.length;
        let currentIndex = 0;

        document.getElementById('next').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
        });

        document.getElementById('prev').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            updateCarousel();
        });

        function updateCarousel() {
            const offset = -currentIndex * 100;
            carousel.style.transform = `translateX(${offset}%)`;
        }
    }
STATIC_JS;

echo "</script></html>";

// Fecha a conexão com o banco de dados
//$conn->close();
?>