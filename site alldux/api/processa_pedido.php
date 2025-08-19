<?php
header('Content-Type: application/json'); // Informa que a resposta será em JSON
session_start();
include('../conexao.php'); // O '../' é para voltar um diretório

// Segurança: Apenas usuários logados podem fazer pedidos
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit;
}

// Recebe os dados do formulário
$id_usuario = $_SESSION['id_usuario'];
$id_produto = $_POST['produto_id'];
$quantidade = (int)$_POST['quantidade'];
$endereco = $_POST['endereco'];
$metodo_pagamento = $_POST['metodo_pagamento'];

// Busca o preço do produto no banco para segurança
$stmt = $conn->prepare("SELECT preco FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id_produto);
$stmt->execute();
$result = $stmt->get_result();
$produto = $result->fetch_assoc();
$preco_unitario = $produto['preco'];
$valor_total = $preco_unitario * $quantidade;

// Inicia uma transação para garantir a consistência dos dados
$conn->begin_transaction();

try {
    // 1. Insere o pedido principal na tabela 'pedidos'
    $sql_pedido = "INSERT INTO pedidos (id_usuario, endereco_entrega, valor_total, metodo_pagamento, status_pagamento) VALUES (?, ?, ?, ?, 'pendente')";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("isds", $id_usuario, $endereco, $valor_total, $metodo_pagamento);
    $stmt_pedido->execute();
    $id_pedido = $conn->insert_id; // Pega o ID do pedido recém-criado

    // 2. Insere o item na tabela 'pedido_itens'
    $sql_item = "INSERT INTO pedido_itens (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);
    $stmt_item->bind_param("iiid", $id_pedido, $id_produto, $quantidade, $preco_unitario);
    $stmt_item->execute();
    
    // Confirma a transação
    $conn->commit();

    // =====================================================================
    // AQUI SERIA A INTEGRAÇÃO COM A API DE PAGAMENTO (MERCADO PAGO, ETC.)
    // Para este exemplo, vamos apenas simular a geração de um PIX
    // =====================================================================
    $pix_data = "CHAVE_PIX_EXEMPLO_PEDIDO_" . $id_pedido; // Dados para o QR Code

    // Retorna uma resposta de sucesso para o JavaScript
    echo json_encode([
        'success' => true, 
        'message' => 'Pedido registrado com sucesso!',
        'metodo' => $metodo_pagamento,
        'pix_data' => $pix_data // Envia os dados do PIX de volta
    ]);

} catch (mysqli_sql_exception $exception) {
    $conn->rollback(); // Desfaz a transação em caso de erro
    echo json_encode(['success' => false, 'message' => 'Erro ao registrar o pedido.']);
}

$stmt->close();
$conn->close();
?>