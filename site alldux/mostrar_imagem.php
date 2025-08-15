<?php
// Conecta ao banco de dados
include('conexao.php');

// Verifica se o parâmetro 'id' foi passado pela URL
if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

    // Consulta para pegar a imagem do banco de dados
    $sql = "SELECT imagem FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($imagem);

    if ($stmt->fetch()) {
        // Define os headers corretos para exibir a imagem em Base64
        $imagem_base64 = base64_encode($imagem);
        $mime_type = "image/jpeg"; // Altere para image/png, se necessário

        echo "<img src='data:$mime_type;base64,$imagem_base64' alt='Imagem do Produto'>";
    } else {
        echo "Imagem não encontrada.";
    }

    $stmt->close();
} else {
    echo "ID do produto não especificado.";
}
?>
