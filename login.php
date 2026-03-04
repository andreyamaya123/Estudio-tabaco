<?php
session_start();
include('conexion.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

   
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        
        if ($fila['contrasena'] === $contrasena) {
            $_SESSION['usuario'] = $fila['nombre_usuario'];
            $_SESSION['rol'] = $fila['rol'];
            header("Location: mostrar_productos.php");
            exit;
        } else {
            $error = "⚠️ Contraseña incorrecta.";
        }
    } else {
        $error = "⚠️ El usuario no existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio de Sesión</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/estilos.css">
</head>

<body class="bg-light p-5">

 
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="mostrar_productos.php"><i class="bi bi-box-seam"></i> TabacoEstudios</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

 
  <div class="container" style="margin-top: 100px;">
    <div class="card shadow-lg mx-auto" style="max-width: 400px;">
      <div class="card-body">
        <h3 class="text-center text-primary mb-4"><i class="bi bi-person-circle"></i> Iniciar Sesión</h3>

        <?php if (!empty($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

        <form method="POST" action="">
          <div class="mb-3">
            <label class="form-label">Usuario:</label>
            <input type="text" name="usuario" class="form-control" maxlength="50" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Contraseña:</label>
            <input type="password" name="contrasena" class="form-control" maxlength="100" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <div class="text-center mt-3">
          <a href="mostrar_productos.php" class="btn btn-outline-secondary w-100">
            <i class="bi bi-house-door"></i> Menú Principal
          </a>
        </div>
      </div>
    </div>
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
