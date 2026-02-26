<?php
include("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Sistema de Detenidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/decoracion.css">
</head>
<body>
<div class="container">

    <div class="text-center mt-3">
        <a href="admin.php" class="btn btn-warning">Panel de Administración</a>
    </div>

    <div class="text-end mt-3">
        <a href="logout.php" class="btn btn-danger btn-sm">Cerrar sesión (no hace nada)</a>
    </div>

    <h2 class="text-center mt-4">Bienvenido</h2>
    <hr>

    <h3 class="text-center">Filtrar Detenciones</h3>
    <form method="GET" action="filtros.php">
        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <select name="delito" class="form-select">
                    <option value="">-- Selecciona Delito --</option>
                    <?php
                    $res = $conexion->query("SELECT * FROM delito");
                    while ($fila = $res->fetch_assoc()) {
                        echo "<option value='" . $fila['id_delito'] . "'>" . htmlspecialchars($fila['tipo_delito']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <input type="date" name="fecha" class="form-control">
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit">Buscar</button>
        </div>
    </form>

    <hr>

    <h3 class="text-center mt-4">Consultas Generales</h3>
    <form method="GET" action="consultas.php">
        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <select name="consulta" class="form-select">
                    <option value="">-- Selecciona una consulta --</option>

                    <option value="paises_por_continente">Total de países por continente</option>
                    <option value="consulados_por_pais">Cantidad de consulados por país</option>
                    <option value="tipos_delito">Listar todos los tipos de delitos</option>
                    <option value="grupos_edad">Mostrar los grupos de edad</option>
                    <option value="situaciones_juridicas">Situaciones jurídicas</option>
                    <option value="detenidos_por_pais">Cantidad de detenidos por país</option>
                    <option value="detenidos_por_continente">Número de detenidos por continente</option>
                    <option value="detenidos_por_delito">Detenidos por tipo de delito</option>
                    <option value="detenidos_por_genero">Detenidos por género</option>
                    <option value="detenidos_por_grupo_edad">Detenidos por grupo de edad</option>
                    <option value="detenidos_por_situacion">Detenidos por situación jurídica</option>
                    <option value="consulados_mas_casos">Consulados con más casos</option>
                    <option value="pais_delito_frecuentes">País y delitos más frecuentes</option>
                    <option value="repatriacion_extradicion">En proceso de repatriación o extradición</option>
                    <option value="pais_mas_femeninas">Países con más detenidas femeninas</option>
                    <option value="delitos_menos_5">Delitos con menos de 5 detenidos</option>
                    <option value="promedio_pais_anio">Promedio de detenidos por país y año</option>
                    <option value="promedio_hombres">Promedio de hombres detenidos</option>
                    <option value="continente_mayor">Continente con más detenidos</option>
                    <option value="continente_menor">Continente con menos detenidos</option>
                </select>
            </div>
        </div>

        <div class="text-center mt-4 mb-5">
            <button class="btn btn-primary">Ejecutar Consulta</button>
        </div>
    </form>

    <div class="text-center mt-5">
        <a href="consola.php" class="btn btn-primary">Ir a Consola SQL</a>
    </div>
</div>

</body>
</html>
