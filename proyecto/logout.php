<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sesión cerrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/decoracion.css">
</head>
<body>
<div class="container text-center">
    <h2>Has cerrado sesión</h2>
    <p class="text-muted">Gracias por usar el sistema de detenidos en el exterior.</p>
    <a href="login.php" class="btn btn-primary mt-3">Volver al inicio de sesión</a>
</div>
</body>
</html>
