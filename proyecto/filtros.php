<?php
include("conexion.php");

// Build where safely
$where = [];

if (!empty($_GET['delito'])) $where[] = "d.id_delito=" . intval($_GET['delito']);
if (!empty($_GET['fecha'])) $where[] = "d.fecha_ingreso='" . $conexion->real_escape_string($_GET['fecha']) . "'";

$where_clause = $where ? "WHERE " . implode(" AND ", $where) : "";

$sql = "
SELECT d.fecha_ingreso, de.tipo_delito, c.nombre_consulado, d.cantidad_detenidos
FROM detencion d
JOIN delito de ON d.id_delito = de.id_delito
JOIN consulado c ON d.id_consulado = c.id_consulado
" . $where_clause;

$resultado = null;
$error = "";
try {
    $resultado = $conexion->query($sql);
} catch (Exception $e) {
    $error = "⚠️ No se pudo realizar el proceso. Verifica los filtros e inténtalo nuevamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados del Filtro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/decoracion.css">
</head>
<body>
<div class="container">
    <h2 class="mb-4">Resultados de la búsqueda</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php elseif ($resultado && $resultado->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Delito</th>
                        <th>Consulado</th>
                        <th>Cant. detenidos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= $fila['fecha_ingreso'] ?></td>
                            <td><?= $fila['tipo_delito'] ?></td>
                            <td><?= $fila['nombre_consulado'] ?></td>
                            <td><?= $fila['cantidad_detenidos'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">No se encontraron resultados para los filtros seleccionados.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Volver</a>
    </div>
</div>
</body>
</html>
