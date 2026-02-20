<?php
session_start();

// Permitir acceso solo a admin y vendedor
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'vendedor'])) {
    header("Location: login.php");
    exit;
}

include('conexion.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>

<body class="bg-light" style="padding-top: 80px;">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="mostrar_productos.php"><i class="bi bi-box-seam"></i> TabacoEstudios</a>
  </div>
</nav>

<div class="container mt-4">
    <h1 class="text-center text-primary mb-4">Panel del Administrador</h1>
    <p class="text-center text-success">
        Bienvenido, <strong><?= $_SESSION['usuario']; ?></strong> (rol: <?= $_SESSION['rol']; ?>)
    </p>

    <!-- ================================
         MENÚ DE CONSULTAS
    ================================= -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-info text-white">
            <i class="bi bi-list-check"></i> Consultas Predefinidas (20 Consultas)
        </div>

        <div class="card-body">

            <form method="POST">
                <label class="form-label fw-bold">Selecciona una consulta:</label>
                <select name="consulta_id" class="form-select mb-3 text-dark" required>
                    <option value="">-- Seleccione una consulta --</option>

                    <option value="1">1. Mostrar todos los productos</option>
                    <option value="2">2. Clasificación y cantidad de productos</option>
                    <option value="3">3. Producto más caro</option>
                    <option value="4">4. Producto más barato</option>
                    <option value="5">5. Total de productos registrados</option>

                    <option value="6">6. Género con mayor prevalencia del último año</option>
                    <option value="7">7. Género con mayor prevalencia del último mes</option>
                    <option value="8">8. Comparación de consumo por género</option>

                    <option value="9">9. Rango de edad con mayor prevalencia anual</option>
                    <option value="10">10. Rango de edad con mayor prevalencia mensual</option>

                    <option value="11">11. Departamentos con mayor edad promedio</option>
                    <option value="12">12. Departamento con mayor mediana de consumo</option>
                    <option value="13">13. Promedio general del consumo por departamento</option>

                    <option value="14">14. Consumo total por producto</option>
                    <option value="15">15. Productos más consumidos por género</option>
                    <option value="16">16. Productos más consumidos por edad</option>
                    <option value="17">17. Productos más consumidos por departamento</option>

                    <option value="18">18. Promedio mensual de consumo por producto</option>
                    <option value="19">19. Consumo total por año</option>
                    <option value="20">20. Ranking general de productos más consumidos</option>
                </select>

                <button type="submit" name="ejecutar_consulta" class="btn btn-info w-100">
                    <i class="bi bi-search"></i> Ejecutar Consulta
                </button>
            </form>

            <?php
            if (isset($_POST['ejecutar_consulta'])) {

                $op = $_POST['consulta_id'];
                $sql = "";

                switch ($op) {
                    case "1": $sql = "SELECT * FROM productos"; break;
                    case "2": $sql = "SELECT clasificacion_pro, COUNT(*) AS total FROM productos GROUP BY clasificacion_pro"; break;
                    case "3": $sql = "SELECT * FROM productos ORDER BY precio DESC LIMIT 1"; break;
                    case "4": $sql = "SELECT * FROM productos ORDER BY precio ASC LIMIT 1"; break;
                    case "5": $sql = "SELECT COUNT(*) AS total_productos FROM productos"; break;
                    case "6": $sql = "SELECT genero, prevalencia_ultimo_ano_pct FROM consumo_genero ORDER BY prevalencia_ultimo_ano_pct DESC LIMIT 1"; break;
                    case "7": $sql = "SELECT genero, prevalencia_ultimo_mes_pct FROM consumo_genero ORDER BY prevalencia_ultimo_mes_pct DESC LIMIT 1"; break;
                    case "8": $sql = "SELECT genero, total_ultimo_ano, total_ultimo_mes FROM consumo_genero"; break;
                    case "9": $sql = "SELECT * FROM consumo_edad ORDER BY prevalencia_ano DESC LIMIT 1"; break;
                    case "10": $sql = "SELECT * FROM consumo_edad ORDER BY prevalencia_mes DESC LIMIT 1"; break;
                    case "11": $sql = "SELECT * FROM consumo_departamento ORDER BY edad_promedio DESC LIMIT 5"; break;
                    case "12": $sql = "SELECT * FROM consumo_departamento ORDER BY mediana DESC LIMIT 1"; break;
                    case "13": $sql = "SELECT AVG(edad_promedio) AS edad_promedio_general, AVG(cve) AS cve_promedio, AVG(mediana) AS mediana_promedio FROM consumo_departamento"; break;
                    case "14": $sql = "SELECT p.nombre_producto, c.total_consumo FROM consumo_producto_total c JOIN productos p ON c.id_producto = p.id_producto"; break;
                    case "15": $sql = "SELECT p.nombre_producto, g.genero, c.cantidad_consumida FROM consumo_producto_genero c JOIN productos p ON p.id_producto = c.id_producto JOIN consumo_genero g ON g.id_genero = c.id_genero ORDER BY cantidad_consumida DESC"; break;
                    case "16": $sql = "SELECT p.nombre_producto, e.rango_edad, c.cantidad_consumida FROM consumo_producto_edad c JOIN productos p ON p.id_producto = c.id_producto JOIN consumo_edad e ON e.id_edad = c.id_edad ORDER BY cantidad_consumida DESC"; break;
                    case "17": $sql = "SELECT p.nombre_producto, d.departamento, c.cantidad_consumida FROM consumo_producto_departamento c JOIN productos p ON p.id_producto = c.id_producto JOIN consumo_departamento d ON d.id_departamento = c.id_departamento ORDER BY cantidad_consumida DESC"; break;
                    case "18": $sql = "SELECT p.nombre_producto, promedio_mensual FROM consumo_producto_total c JOIN productos p ON c.id_producto = p.id_producto"; break;
                    case "19": $sql = "SELECT anio, SUM(total_consumo) AS consumo_total FROM consumo_producto_total GROUP BY anio"; break;
                    case "20": $sql = "SELECT p.nombre_producto, SUM(total_consumo) AS total FROM consumo_producto_total c JOIN productos p ON p.id_producto = c.id_producto GROUP BY c.id_producto ORDER BY total DESC"; break;
                }

                $resultado = $conexion->query($sql);

                echo "<h4 class='mt-4'>Resultado de la consulta:</h4>";

                if ($resultado && $resultado->num_rows > 0) {
                    echo "<table class='table table-striped table-bordered mt-3'><thead class='table-dark'><tr>";

                    while ($campo = $resultado->fetch_field()) {
                        echo "<th>{$campo->name}</th>";
                    }

                    echo "</tr></thead><tbody>";

                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($fila as $valor) {
                            echo "<td>" . htmlspecialchars($valor) . "</td>";
                        }
                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-warning mt-3'>No hay resultados para esta consulta.</div>";
                }
            }
            ?>
        </div>
    </div>

    <!-- ======================
          CONSOLA SQL CONTROLADA
    =======================-->
    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            <i class="bi bi-terminal"></i> Consola SQL (SELECT + INSERT solo admin)
        </div>
        <div class="card-body">

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Escribe tu consulta SQL:</label>
                    <textarea name="sql" class="form-control" rows="4" placeholder="Ejemplo: SELECT * FROM productos;" required></textarea>
                </div>
                <button type="submit" name="ejecutar" class="btn btn-danger">
                    <i class="bi bi-play-fill"></i> Ejecutar Consulta
                </button>
            </form>

            <?php
            if (isset($_POST['ejecutar'])) {

                $consulta = trim($_POST['sql']);
                $consulta_limpia = strtoupper($consulta);
                $esAdmin = ($_SESSION['rol'] === 'admin');

                // ❌ Prohibir INSERT al vendedor
                if (!$esAdmin && strpos($consulta_limpia, "INSERT") === 0) {
                    echo "<div class='alert alert-danger mt-3'>
                        ❌ Solo el administrador puede insertar datos.
                    </div>";
                    exit;
                }

                // Comandos permitidos para vendedor y admin
                $permitidos = ["SELECT", "SHOW", "DESCRIBE", "EXPLAIN"];

                if ($esAdmin) {
                    $permitidos[] = "INSERT"; // Admin si puede INSERT
                }

                $comandoPermitido = false;
                foreach ($permitidos as $cmd) {
                    if (strpos($consulta_limpia, $cmd) === 0) {
                        $comandoPermitido = true;
                        break;
                    }
                }

                if (!$comandoPermitido) {
                    echo "<div class='alert alert-danger mt-3'>
                        ❌ Comando no permitido.<br>
                        Permitidos: SELECT, SHOW, DESCRIBE, EXPLAIN" . ($esAdmin ? ", INSERT" : "") . "
                    </div>";
                    exit;
                }

                // Ejecutar consulta
                try {
                    $resultado = $conexion->query($consulta);

                    if ($resultado instanceof mysqli_result) {

                        echo "<h5 class='mt-4'>Resultado:</h5>";
                        echo "<table class='table table-bordered table-striped mt-3'>";
                        echo "<thead class='table-dark'><tr>";

                        while ($campo = $resultado->fetch_field()) {
                            echo "<th>{$campo->name}</th>";
                        }

                        echo "</tr></thead><tbody>";

                        while ($fila = $resultado->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($fila as $valor) {
                                echo "<td>$valor</td>";
                            }
                            echo "</tr>";
                        }

                        echo "</tbody></table>";

                    } else {
                        echo "<div class='alert alert-success mt-3'>
                            ✔ Comando ejecutado correctamente.
                        </div>";
                    }

                } catch (Exception $e) {
                    echo "<div class='alert alert-danger mt-3'>Error: " . $e->getMessage() . "</div>";
                }
            }
            ?>
        </div>
    </div>

    <a href="mostrar_productos.php" class="btn btn-secondary mt-4">
        <i class="bi bi-arrow-left-circle"></i> Menú Principal
    </a>

</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
  <div class="container">
      &copy; <?= date("Y"); ?> <strong>TabacoEstudios</strong>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
