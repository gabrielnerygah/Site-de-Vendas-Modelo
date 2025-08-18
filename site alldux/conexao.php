<?php
// Detalhes da conexão com o banco de dados da Alwaysdata
$servidor   = "mysql-bdteste.alwaysdata.net";  // <-- Retirado da sua imagem
$banco      = "bdteste_1";                     // <-- Retirado da sua imagem
$usuario_db = "bdteste";                     // <-- Nome mais provável do usuário, confirme no painel
$senha_db   = "gdml22052007";            // <-- COLOQUE A SENHA QUE VOCÊ CRIOU AQUI

// Cria a conexão
$conn = new mysqli($servidor, $usuario_db, $senha_db, $banco);

// Verifica a conexão
if ($conn->connect_error) {
    // Para o erro na execução e exibe a mensagem de erro.
    // Em um site real, você pode querer registrar o erro em um log em vez de exibi-lo.
    die("Falha na conexão: " . $conn->connect_error);
}

// Define o charset para UTF-8 para evitar problemas com acentos
mysqli_set_charset($conn, "utf8mb4");

?>