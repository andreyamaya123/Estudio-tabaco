<?php
session_start();
include('conexion.php');

$totalProductos = $conexion->query("SELECT COUNT(*) AS total FROM productos")->fetch_assoc()['total'];
$totalCantidad = $conexion->query("SELECT SUM(cantidad) AS total FROM productos")->fetch_assoc()['total'];
$precioPromedio = $conexion->query("SELECT AVG(precio) AS promedio FROM productos")->fetch_assoc()['promedio'];
$productoCaro = $conexion->query("SELECT nombre_producto, precio FROM productos ORDER BY precio DESC LIMIT 1")->fetch_assoc();

$sql = "SELECT id_producto, clasificacion_pro, nombre_producto, cantidad, precio FROM productos";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de Productos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/estilos.css">
</head>

<body class="bg-light fade-in">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href=mostrar_productos.php><i class="bi bi-box-seam"></i>TabacoEstudios</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        <?php if (isset($_SESSION['usuario'])): ?>

          <!-- ✔ SOLO el admin puede agregar -->
          <?php if ($_SESSION['rol'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="insertar_productos.php">Agregar</a></li>
          <?php endif; ?>

          <!-- ✔ admin y vendedor pueden ver CONSULTAS -->
          <?php if (in_array($_SESSION['rol'], ['admin', 'vendedor'])): ?>
            <li class="nav-item"><a class="nav-link" href="consultas_admin.php">Consultas</a></li>
          <?php endif; ?>

          <!-- cerrar sesión -->
          <li class="nav-item">
            <a class="nav-link text-warning" href="logout.php">
              <i class="bi bi-box-arrow-right"></i> Salir
            </a>
          </li>

        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Iniciar sesión</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<div class="container" style="margin-top:90px;">

  <div class="text-center mb-4">
    <h1 class="text-primary fw-bold"><i class="bi bi-graph-up"></i> Resumen de Productos</h1>
    <?php if (isset($_SESSION['usuario'])): ?>
      <p class="text-success">Conectado como <strong><?= $_SESSION['usuario'] ?></strong> (<?= $_SESSION['rol'] ?>)</p>
    <?php endif; ?>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm bg-primary text-white">
        <i class="bi bi-archive fs-1 mb-2"></i>
        <h5>Total de Productos</h5>
        <h3><?= $totalProductos ?></h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm bg-success text-white">
        <i class="bi bi-stack fs-1 mb-2"></i>
        <h5>Unidades Totales</h5>
        <h3><?= number_format($totalCantidad) ?></h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm bg-warning text-dark">
        <i class="bi bi-cash-stack fs-1 mb-2"></i>
        <h5>Precio Promedio</h5>
        <h3>$<?= number_format($precioPromedio, 3, ',', '.') ?></h3>
      </div>
    </div>

    <div class="col-md-3 d-flex align-items-stretch">
      <div class="card text-center p-3 shadow-sm bg-danger text-white h-100 w-100">
        <i class="bi bi-star-fill fs-1 mb-2"></i>
        <h5>Más Caro</h5>
        <h6 class="mb-2"><?= $productoCaro['nombre_producto']; ?></h6>
        <p class="fw-bold fs-5 mb-0">$<?= number_format($productoCaro['precio'], 3, ',', '.') ?></p>
      </div>
    </div>
  </div>

  <div class="text-center mb-4">
    <button id="toggleTabla" class="btn btn-outline-primary">
      <i class="bi bi-table"></i> Productos nicotina
    </button>
  </div>

  <div id="tablaContainer" class="table-container" style="display:none;">
    <table class="table table-hover table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nombre del Producto</th>
          <th>Tipo de Producto</th>
          <th>Cantidad</th>
          <th>Precio (COP)</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($resultado->num_rows > 0) {
          while($fila = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>{$fila['id_producto']}</td>
                    <td>{$fila['nombre_producto']}</td>
                    <td>{$fila['clasificacion_pro']}</td>
                    <td>{$fila['cantidad']}</td>
                    <td>$" . number_format($fila['precio'], 3, ',', '.') . "</td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='5' class='text-center'>No hay productos registrados</td></tr>";
        }
        $conexion->close();
        ?>
      </tbody>
    </table>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("toggleTabla").addEventListener("click", function() {
  const tabla = document.getElementById("tablaContainer");
  tabla.style.display = tabla.style.display === "none" ? "block" : "none";
  this.innerHTML = tabla.style.display === "block"
    ? '<i class="bi bi-eye-slash"></i> Ocultar Tabla'
    : '<i class="bi bi-table"></i> Productos nicotina';
});
</script>

<footer class="bg-dark text-white text-center py-3 mt-5">
  <div class="container">
    <p class="mb-1">
      &copy; <?= date("Y"); ?> <strong>TabacoEstudios</strong> — Todos los derechos reservados.
    </p>
    <p class="small mb-0">
      Desarrollado por el equipo de <span class="text-primary fw-bold">Desarrollo informatico ECCI</span>.
    </p>
  </div>
</footer>

</body>
</html>
