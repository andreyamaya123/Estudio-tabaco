<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Normalizar usuario
    $usuario = trim(strtolower($_POST['usuario']));
    $password = trim($_POST['password']);

    // Sentencia preparada
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();

        // Verificar contraseña
        if (password_verify($password, $row['password'])) {

            // Guardar sesión
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['role'] = isset($row['role']) ? $row['role'] : 'cliente';

            header("Location: index.php");
            exit;

        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/decoracion.css">
</head>
<body>
<div class="container text-center mt-5">
    <h2>Inicio de Sesión</h2>

    <?php if (!empty($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>

    <form method="POST" class="mt-4">

        <div class="mb-3">
            <input type="email" class="form-control" name="usuario" placeholder="Correo electrónico" required>
        </div>

        <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
        </div>

        <button type="submit">Ingresar</button>

        <p class="mt-3">¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </form>
</div>
</body>
</html>
