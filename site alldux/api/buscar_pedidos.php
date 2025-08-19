<?php
header('Content-Type: application/json');
session_start();
include('../conexao.php');

// Apenas usuários logados podem ver seus pedidos
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Busca todos os pedidos do usuário logado, do mais recente para o mais antigo
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY data_pedido DESC");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

echo json_encode(['success' => true, 'pedidos' => $pedidos]);

$stmt->close();
$conn->close();
?>