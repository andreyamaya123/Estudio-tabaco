<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include("conexion.php");

$mensaje = "";

// Manejar creación de usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevoUser = $_POST['usuario'];
    $nuevoPass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $nuevoRol = $_POST['rol'];

    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nuevoUser, $nuevoPass, $nuevoRol);

    if ($stmt->execute()) {
        $mensaje = "Usuario creado correctamente.";
    } else {
        $mensaje = "Error al crear usuario: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/estilos.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Panel de Administración</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info text-center mt-3"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <div class="card mt-4 p-4">
        <h4>Crear nuevo usuario</h4>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select">
                    <option value="cliente">Cliente</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <div class="text-center">
                <button class="btn btn-primary">Crear usuario</button>
            </div>
        </form>
    </div>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Volver</a>
    </div>
</div>
</body>
</html>
