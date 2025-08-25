<?php
// Este arquivo assume que a variável $conn (conexão com o banco) já existe.

// 1. BUSCA DOS DADOS
$sql = "SELECT 
            p.id, 
            p.nome, 
            p.descricao, 
            p.preco, 
            p.imagem, 
            u.nome AS promotor_nome 
        FROM 
            produtos AS p
        JOIN 
            usuarios AS u ON p.id_usuario = u.id
        ORDER BY 
            p.id DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta ao banco de dados: " . $conn->error);
}

// Salva todos os produtos em array para poder reaproveitar (apenas uma vez)
$produtos = [];
while ($row = $result->fetch_assoc()) {
    $produtos[] = $row;
}

// Libera memória após o uso
$result->free();

// Define a variável da imagem de fundo AQUI, antes de tudo.
// Pega a imagem do primeiro produto, se houver produtos.
$imagem_fundo_inicial = "url('caminho/para/imagem_padrao.jpg')"; // Imagem de fundo padrão caso não haja produtos
if (!empty($produtos)) {
    // Notei que no código que você me enviou, a linha abaixo estava errada.
    // O correto é pegar o item 0 do array, que é o primeiro produto.
    $imagem_fundo_inicial = "url('" . htmlspecialchars($produtos[0]['imagem']) . "')";
}

?>

<div class="parallax" style="background-image: <?php echo $imagem_fundo_inicial; ?>;"></div>


<div class="container">
    <div class="box2">
        <div class="container2">
            <h3>Produtos Disponíveis</h3>
            <?php if (count($produtos) > 0): ?>
                <div class="product-list">
                    <?php foreach ($produtos as $produto): ?>
                        <?php
                            // Prepara variáveis para exibição segura dentro do loop
                            $produto_id = htmlspecialchars($produto['id']);
                            $produto_imagem = htmlspecialchars($produto['imagem']);
                            $produto_nome = htmlspecialchars($produto['nome']);
                            $produto_descricao = htmlspecialchars($produto['descricao']);
                            $produto_preco = number_format($produto['preco'], 2, ',', '.');
                            $promotor_nome = htmlspecialchars($produto['promotor_nome']);
                        ?>
                        <div class="product">
                            <a href="index.php?i=produto&id=<?php echo $produto_id; ?>">
                                <img src="<?php echo $produto_imagem; ?>" alt="<?php echo $produto_nome; ?>">
                                <div class="product-info-top">
                                    <strong><?php echo $produto_nome; ?></strong>
                                    <p><?php echo $produto_descricao; ?></p>
                                </div>
                            </a>
                            <div class="product-info-bottom">
                                <span>R$ <?php echo $produto_preco; ?></span>
                                <button class="btn-add-carrinho" onclick="adicionarAoCarrinho(<?php echo $produto_id; ?>)">
                                    <i class="fas fa-cart-plus"></i> Adicionar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <br>
                <hr><br>
            <?php else: ?>
                <p>Nenhum produto disponível no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</div>