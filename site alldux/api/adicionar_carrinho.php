<?php
// Define que a resposta será em formato JSON
header('Content-Type: application/json');
session_start();

// Garante que o carrinho exista na sessão
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$response = [
    'success' => false,
    'novaContagem' => 0
];

// Verifica se o ID do produto foi enviado via POST
if (isset($_POST['produto_id'])) {
    $produto_id = (int)$_POST['produto_id'];

    // Se o produto já está no carrinho, incrementa a quantidade
    if (isset($_SESSION['carrinho'][$produto_id])) {
        $_SESSION['carrinho'][$produto_id]++;
    } else {
        // Se não, adiciona com quantidade 1
        $_SESSION['carrinho'][$produto_id] = 1;
    }

    $response['success'] = true;
}

// Calcula e retorna o número total de itens únicos no carrinho
$response['novaContagem'] = count($_SESSION['carrinho']);

// Envia a resposta de volta para o JavaScript
echo json_encode($response);
?>