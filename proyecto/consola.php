<?php
session_start();

include("conexion.php");

// Obtener el rol del usuario
$rol = $_SESSION['role'] ?? 'cliente';

$resultado = null;
$error = "";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $consulta = trim($_POST['consulta']);

    if ($consulta !== "") {

        // Si el rol no es admin, restringir consultas que modifiquen la base
        if ($rol !== 'admin') {
            $prohibidas = ['INSERT', 'UPDATE', 'DELETE', 'DROP', 'ALTER', 'CREATE', 'TRUNCATE'];
            foreach ($prohibidas as $palabra) {
                if (stripos($consulta, $palabra) === 0) {
                    $error = "⚠️ No tienes permisos para ejecutar esa operación. 
                              Los usuarios cliente solo pueden ejecutar consultas SELECT.";
                    $consulta = "";
                    break;
                }
            }
        }

        if ($consulta !== "") {
            try {
                $resultado = $conexion->query($consulta);

                if ($resultado instanceof mysqli_result) {
                    $mensaje = "✅ Consulta ejecutada correctamente.";
                } else {
                    $mensaje = "✅ Operación realizada con éxito.";
                }

            } catch (Exception $e) {
                $error = "⚠️ No se pudo realizar el proceso. Verifica la consulta e inténtalo nuevamente.";
            }
        }
    } else {
        $error = "Por favor, escribe una consulta antes de ejecutar.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consola SQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/decoracion.css">
</head>
<body>
<div class="container">
    <div class="text-end mt-3">
        <a href="index.php" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    <h2 class="text-center mt-3">Consola SQL</h2>
    <p class="text-center">Ejecuta tus consultas SQL directamente en la base de datos.</p>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($mensaje): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <textarea name="consulta" class="form-control" rows="5"><?= isset($_POST['consulta']) ? htmlspecialchars($_POST['consulta']) : '' ?></textarea>
        </div>
        <div class="text-center">
            <button type="submit">Ejecutar</button>
        </div>
    </form>

    <?php if ($resultado && $resultado instanceof mysqli_result && $resultado->num_rows > 0): ?>
        <div class="table-responsive mt-4">
            <table class="table table-dark table-striped table-hover">
                <thead>
                    <tr>
                        <?php foreach ($resultado->fetch_fields() as $col): ?>
                            <th><?= htmlspecialchars($col->name) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <?php foreach ($fila as $valor): ?>
                                <td><?= htmlspecialchars($valor) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($resultado && $resultado instanceof mysqli_result && $resultado->num_rows === 0): ?>
        <div class="alert alert-warning text-center mt-4">No se encontraron resultados.</div>
    <?php endif; ?>
</div>
</body>
</html>
