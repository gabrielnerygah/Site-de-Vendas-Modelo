<?php
session_start();
session_unset();
session_destroy();

// Expirar cookies
setcookie("id_usuario", "", time() - 3600, "/");
setcookie("nome_usuario", "", time() - 3600, "/");
setcookie("foto_perfil", "", time() - 3600, "/");

// Redirecionar para a página de login
header("Location: login.php");
exit();
?>
