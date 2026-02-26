<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Normalizar correo del usuario
    $usuario = trim(strtolower($conexion->real_escape_string($_POST['usuario'])));
    $passwordPlano = trim($_POST['password']);

    // Hash de contraseña
    $password = password_hash($passwordPlano, PASSWORD_BCRYPT);

    // Comprobar si existe la columna role
    $colCheck = $conexion->query("SHOW COLUMNS FROM usuarios LIKE 'role'");

    if ($colCheck && $colCheck->num_rows > 0) {

        // Registrar usuario normal (cliente)
        $stmt = $conexion->prepare(
            "INSERT INTO usuarios (usuario, password, role) VALUES (?, ?, 'cliente')"
        );
        $stmt->bind_param("ss", $usuario, $password);

    } else {

        // Registrar sin rol (para tablas viejas)
        $stmt = $conexion->prepare(
            "INSERT INTO usuarios (usuario, password) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $usuario, $password);
    }

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Error al registrar: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/decoracion.css">
</head>
<body>
<div class="container text-center mt-5">
    <h2>Registro de Usuario</h2>

    <?php if (!empty($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>

    <form method="POST" class="mt-4">

        <div class="mb-3">
            <input type="email" class="form-control" name="usuario"
                   placeholder="Correo electrónico" required>
        </div>

        <div class="mb-3">
            <input type="password" class="form-control" name="password"
                   placeholder="Contraseña" required>
        </div>

        <button type="submit">Registrar</button>

        <p class="mt-3">
            <a href="login.php">Volver al login</a>
        </p>
    </form>
</div>
</body>
</html>
