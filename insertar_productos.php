<?php
session_start();
if (!isset($_SESSION['usuario']) || 
   ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'vendedor')) {
    header("Location: login.php");
    exit;
}

include('conexion.php');

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $clasificacion = $_POST['clasificacion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    $stmt = $conexion->prepare("INSERT INTO productos (nombre_producto, clasificacion_pro, cantidad, precio) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $nombre, $clasificacion, $cantidad, $precio);

    if ($stmt->execute()) {
        $mensaje = "✅ Producto agregado correctamente.";
    } else {
        $mensaje = "❌ Error al agregar producto: " . $stmt->error;
    }

    $stmt->close();
}

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>

<body class="bg-light p-4" style="padding-top: 80px;"> 

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="mostrar_productos.php">
        <i class="bi bi-box-seam"></i> TabacoEstudios
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
      </button>

    </div>
  </nav>
  <div>
    <br>
    <br>
    <br>
    <br>
  </div>

  <div class="container mt-4">
      <h1 class="text-center text-success mb-4">Agregar nuevo producto</h1>

      <?php if (!empty($mensaje)) echo "<div class='alert alert-info text-center'>$mensaje</div>"; ?>

      <form method="POST" action="">
          <div class="mb-3">
              <label class="form-label">Nombre del producto:</label>
              <input type="text" name="nombre" class="form-control" maxlength="60" required>
          </div>

          <div class="mb-3">
              <label class="form-label">Tipo de Producto:</label>
              <select name="clasificacion" class="form-select" required>
                  <option value="">Seleccione el tipo de producto</option>
                  <option value="Cigarrillos">Cigarrillos</option>
                  <option value="Tabaco Elaborado">Tabaco Elaborado</option>
                  <option value="Otras formas de tabaco elaborado">Otras formas de tabaco elaborado</option>
                  <option value="Picadura, Rapú y Chimú">Picadura, Rapú y Chimú</option>
              </select>
          </div>

          <div class="mb-3">
              <label class="form-label">Cantidad:</label>
              <input type="number" name="cantidad" class="form-control" min="1" required>
          </div>

          <div class="mb-3">
              <label class="form-label">Precio:</label>
              <input type="number" step="0.01" name="precio" class="form-control" min="0" required>
          </div>

          <button type="submit" class="btn btn-success w-100 mb-2">
              <i class="bi bi-save"></i> Guardar producto
          </button>
          <a href="mostrar_productos.php" class="btn btn-secondary w-100">
              <i class="bi bi-arrow-left-circle"></i> Menú Principal
          </a>
      </form>
  </div>

  <footer class="bg-dark text-white text-center py-3 mt-5">
    <div class="container">
      <p class="mb-1">
        &copy; <?= date("Y"); ?> <strong>TabacoEstudios</strong> — Todos los derechos reservados.
      </p>
      <p class="small mb-0">
        Desarrollado por el equipo de <span class="text-primary fw-bold">Desarrollo Informático ECCI</span>.
      </p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
