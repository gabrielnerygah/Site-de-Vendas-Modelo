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

// Salva todos os produtos em array para poder reaproveitar
$produtos = [];
while ($row = $result->fetch_assoc()) {
    $produtos[] = $row;
}

// Libera memória
$result->free();
?>


<h1>Rádio Canadá Brasil</h1>
<div class="container">
    <div class="box2">
        <div class="container2">
            <h3>Produtos Disponíveis</h3>
            <?php if (count($produtos) > 0): ?>
                <div class="product-list">
                    <?php foreach ($produtos as $produto): ?>
                        <div class="product">
                            <a href="#" onclick="abrirModalCompra(<?php echo htmlspecialchars(json_encode($produto)); ?>)">
                                <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                    alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <div class="product-info">
                                    <strong><?php echo htmlspecialchars($produto['nome']); ?></strong>
                                    <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
                                    <span>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                                    <small>Vendido por: <?php echo htmlspecialchars($produto['promotor_nome']); ?></small>
                                </div>
                            </a>
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