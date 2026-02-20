<?php
$host = "localhost";      // Servidor (en XAMPP siempre es localhost)
$usuario = "root";        // Usuario por defecto de MySQL
$clave = "";              // Contraseña vacía (por defecto en XAMPP)
$base_datos = "estudio_tabaco"; // Nombre de tu base

// Crear la conexión
$conexion = new mysqli($host, $usuario, $clave, $base_datos);

// Verificar si hay errores
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
} else {
    echo "";
}
?>
    