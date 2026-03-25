<?php
// ========================================
// 🔌 Conexión a la Base de Datos PixelPro
// ========================================

// Datos de conexión
$host = "localhost";      // Servidor MySQL
$usuario = "root";        // Usuario MySQL
$password = "";           // Contraseña MySQL
$basedatos = "pixelpro";  // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($host, $usuario, $password, $basedatos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer codificación
$conexion->set_charset("utf8mb4");
?>