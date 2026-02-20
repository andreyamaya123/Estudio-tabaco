<?php
session_start(); // Reanuda la sesión actual
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión completamente

// Redirige al usuario al login (o podrías mandarlo a mostrar_productos.php)
header("Location: mostrar_productos.php");
exit;
?>
