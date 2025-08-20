<?php
header('Content-Type: application/json');
session_start();
include('../conexao.php');

// Se o carrinho estiver vazio ou não existir, retorna uma resposta vazia
if (empty($_SESSION['carrinho'])) {
    echo json_encode(['success' => true, 'itens' => []]);
    exit;
}

// Pega os IDs dos produtos que estão no carrinho
$ids_produtos = array_keys($_SESSION['carrinho']);

// Cria placeholders para a consulta SQL (ex: ?,?,?)
$placeholders = implode(',', array_fill(0, count($ids_produtos), '?'));
$tipos = str_repeat('i', count($ids_produtos)); // 'i' para cada ID (integer)

// Busca os detalhes de todos os produtos do carrinho em uma única consulta
$sql = "SELECT id, nome, preco, imagem FROM produtos WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->bind_param($tipos, ...$ids_produtos);
$stmt->execute();
$result = $stmt->get_result();

$itens_carrinho = [];
while ($produto = $result->fetch_assoc()) {
    $produto_id = $produto['id'];
    // Adiciona a quantidade (que está na sessão) aos detalhes do produto
    $produto['quantidade'] = $_SESSION['carrinho'][$produto_id];
    $itens_carrinho[] = $produto;
}

echo json_encode(['success' => true, 'itens' => $itens_carrinho]);

$stmt->close();
$conn->close();
?>