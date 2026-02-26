<?php
session_start();

include("conexion.php");

$consulta = $_GET['consulta'] ?? '';
$titulos = [
    "paises_por_continente" => "Total de países por continente",
    "consulados_por_pais" => "Cantidad de consulados por país",
    "tipos_delito" => "Listado de tipos de delitos",
    "grupos_edad" => "Listado de grupos de edad",
    "situaciones_juridicas" => "Listado de situaciones jurídicas",
    "detenidos_por_pais" => "Cantidad total de detenidos por país",
    "detenidos_por_continente" => "Cantidad total de detenidos por continente",
    "detenidos_por_delito" => "Detenidos por tipo de delito",
    "detenidos_por_genero" => "Detenidos por género",
    "detenidos_por_grupo_edad" => "Detenidos por grupo de edad",
    "detenidos_por_situacion" => "Detenidos por situación jurídica",
    "consulados_mas_casos" => "Consulados que más casos manejan",
    "pais_delito_frecuentes" => "País y delitos más frecuentes",
    "repatriacion_extradicion" => "Casos en repatriación o extradición",
    "pais_mas_femeninas" => "Países con más detenidas femeninas",
    "delitos_menos_5" => "Delitos con menos de 5 detenidos",
    "promedio_pais_anio" => "Promedio de detenidos por país y año",
    "promedio_hombres" => "Promedio de hombres detenidos",
    "continente_mayor" => "Continente con mayor número de detenidos",
    "continente_menor" => "Continente con menor número de detenidos"
];
$consultas_graficables = [
    "paises_por_continente",
    "consulados_por_pais",
    "detenidos_por_pais",
    "detenidos_por_continente",
    "detenidos_por_delito",
    "detenidos_por_genero",
    "detenidos_por_grupo_edad",
    "detenidos_por_situacion",
    "consulados_mas_casos",
    "pais_delito_frecuentes",
    "pais_mas_femeninas",
    "delitos_menos_5",
    "promedio_pais_anio",
    "promedio_hombres",
    "continente_mayor",
    "continente_menor"
];


$queries = [
    "paises_por_continente" =>
        "SELECT c.nombre_continente, COUNT(p.id_pais) AS total_paises
         FROM pais_prision p
         JOIN continente c ON p.id_continente = c.id_continente
         GROUP BY c.nombre_continente",

    "consulados_por_pais" =>
        "SELECT p.nombre_pais, COUNT(co.id_consulado) AS Cantidad_Consulados
         FROM consulado co
         JOIN pais_prision p ON co.id_pais = p.id_pais
         GROUP BY p.nombre_pais
         ORDER BY Cantidad_Consulados DESC",

    "tipos_delito" => "SELECT * FROM delito",
    "grupos_edad" => "SELECT * FROM grupoedad",
    "situaciones_juridicas" => "SELECT * FROM situacionjuridica",

    "detenidos_por_pais" =>
        "SELECT p.nombre_pais, SUM(d.cantidad_detenidos) AS total_detenidos
         FROM detencion d
         JOIN pais_prision p ON d.id_pais = p.id_pais
         GROUP BY p.nombre_pais
         ORDER BY total_detenidos DESC",

    "detenidos_por_continente" =>
        "SELECT c.nombre_continente, SUM(d.cantidad_detenidos) AS total_detenidos
         FROM detencion d
         JOIN pais_prision p ON d.id_pais = p.id_pais
         JOIN continente c ON p.id_continente = c.id_continente
         GROUP BY c.nombre_continente",

    "detenidos_por_delito" =>
        "SELECT de.tipo_delito, SUM(d.cantidad_detenidos) AS total
         FROM detencion d
         JOIN delito de ON d.id_delito = de.id_delito
         GROUP BY de.tipo_delito
         ORDER BY total DESC",

    "detenidos_por_genero" =>
        "SELECT s.descripcion AS sexo, SUM(d.cantidad_detenidos) AS total
         FROM detencion d
         JOIN sexo s ON d.id_sexo = s.id_sexo
         GROUP BY s.descripcion
         ORDER BY total DESC",

    "detenidos_por_grupo_edad" =>
        "SELECT g.rango_edad, SUM(d.cantidad_detenidos) AS total
         FROM detencion d
         JOIN grupoedad g ON d.id_grupo_edad = g.id_grupo_edad
         GROUP BY g.rango_edad
         ORDER BY total DESC",

    "detenidos_por_situacion" =>
        "SELECT sj.descripcion AS situacion, SUM(d.cantidad_detenidos) AS total
         FROM detencion d
         JOIN situacionjuridica sj ON d.id_situacion = sj.id_situacion
         GROUP BY sj.descripcion",

    "consulados_mas_casos" =>
        "SELECT co.nombre_consulado, p.nombre_pais,
                SUM(d.cantidad_detenidos) AS total_casos
         FROM detencion d
         JOIN consulado co ON d.id_consulado = co.id_consulado
         JOIN pais_prision p ON co.id_pais = p.id_pais
         GROUP BY co.nombre_consulado, p.nombre_pais
         ORDER BY total_casos DESC",

    "pais_delito_frecuentes" =>
        "SELECT p.nombre_pais, de.tipo_delito,
                SUM(d.cantidad_detenidos) AS total
         FROM detencion d
         JOIN pais_prision p ON d.id_pais = p.id_pais
         JOIN delito de ON d.id_delito = de.id_delito
         GROUP BY p.nombre_pais, de.tipo_delito
         ORDER BY total DESC
         LIMIT 10",

    "repatriacion_extradicion" =>
        "SELECT te.descripcion AS tipo_proceso,
            SUM(d.cantidad_detenidos) AS total_detenidos
         FROM Detencion d
         JOIN TipoExtradicion te ON d.id_extradicion = te.id_extradicion
         WHERE te.descripcion IN ('EXTRADICION', 'REPATRIACION', 'EXTRADICION, REPATRIACION')
         GROUP BY te.descripcion
         ORDER BY total_detenidos DESC;
",

    "pais_mas_femeninas" =>
        "SELECT p.nombre_pais, SUM(d.cantidad_detenidos) AS total_femenino
         FROM detencion d
         JOIN pais_prision p ON d.id_pais = p.id_pais
         JOIN sexo s ON d.id_sexo = s.id_sexo
         WHERE s.descripcion = 'F'
         GROUP BY p.nombre_pais
         ORDER BY total_femenino DESC",

    "delitos_menos_5" =>
        "SELECT de.tipo_delito,
                SUM(d.cantidad_detenidos) AS total_detenidos
         FROM detencion d
         JOIN delito de ON d.id_delito = de.id_delito
         GROUP BY de.tipo_delito
         HAVING total_detenidos < 5
         ORDER BY total_detenidos ASC",

    "promedio_pais_anio" =>
        "SELECT p.nombre_pais, YEAR(d.fecha_ingreso) AS anio,
                AVG(d.cantidad_detenidos) AS promedio_detenidos
         FROM detencion d
         JOIN pais_prision p ON d.id_pais = p.id_pais
         GROUP BY p.nombre_pais, anio
         ORDER BY anio, promedio_detenidos DESC",

    "promedio_hombres" =>
        "SELECT s.descripcion AS sexo,
                AVG(d.cantidad_detenidos) AS promedio_hombres
         FROM detencion d
         JOIN sexo s ON d.id_sexo = s.id_sexo
         WHERE s.descripcion = 'M'",

    "continente_mayor" =>
        "SELECT c.nombre_continente, SUM(d.cantidad_detenidos) AS total_detenidos
         FROM detencion d
         JOIN pais_prision p ON d.id_pais = p.id_pais
         JOIN continente c ON p.id_continente = c.id_continente
         GROUP BY c.nombre_continente
         ORDER BY total_detenidos DESC LIMIT 1",

    "continente_menor" =>
        "SELECT c.nombre_continente, SUM(d.cantidad_detenidos) AS total_detenidos
         FROM detencion d
         JOIN pais_prision p ON d.id_pais = p.id_pais
         JOIN continente c ON p.id_continente = c.id_continente
         GROUP BY c.nombre_continente
         ORDER BY total_detenidos ASC LIMIT 1"
];

if (!isset($queries[$consulta])) {
    die("Consulta inválida.");
}

$sql = $queries[$consulta];
$result = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado de Consulta</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2 class="text-center">
    <?php echo $titulos[$consulta] ?? "Resultado de la consulta"; ?>
</h2>
<?php if (in_array($consulta, $consultas_graficables)): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <canvas id="grafica" style="max-height: 400px;"></canvas>

    <script>
        const labels = [];
        const dataValues = [];
    </script>
<?php else: ?>
    <div class="text-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/512/1827/1827951.png"
             width="70">
        <p><strong>Consulta informativa sin datos numéricos.</strong></p>
    </div>
<?php endif; ?>


<a href="index.php" class="btn btn-secondary mb-3">⬅ Regresar</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <?php
            if ($result->num_rows > 0) {
                foreach ($result->fetch_fields() as $campo) {
                    echo "<th>" . htmlspecialchars($campo->name) . "</th>";
                }
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $result->data_seek(0);
       while ($fila = $result->fetch_assoc()) {

    // Primer campo = etiqueta
    $etiqueta = array_values($fila)[0];
    // Segundo campo = valor numérico (si existe)
    $valor = is_numeric(array_values($fila)[1] ?? null) ? array_values($fila)[1] : null;

    echo "<tr>";
    foreach ($fila as $v) {
        echo "<td>" . htmlspecialchars($v) . "</td>";
    }
    echo "</tr>";

    // Si es graficable, preparar datos
    if (in_array($consulta, $consultas_graficables) && $valor !== null) {
        echo "<script>
                labels.push('".htmlspecialchars($etiqueta)."');
                dataValues.push(".htmlspecialchars($valor).");
              </script>";
    }
}

        ?>
    </tbody>
</table>
<?php if (in_array($consulta, $consultas_graficables)): ?>

<script>
const ctx = document.getElementById('grafica');

new Chart(ctx, {
    type: '<?php echo ($consulta === "promedio_pais_anio") ? "line" : "bar"; ?>',
    data: {
        labels: labels,
        datasets: [{
            label: '<?php echo $titulos[$consulta]; ?>',
            data: dataValues
        }]
    }
});
</script>

<?php endif; ?>

</body>
</html>

