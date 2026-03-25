<?php
// ========================================
// 🔌 Conexión a la Base de Datos PixelPro
// ========================================

// Datos de conexión
$host = "localhost";      // Servidor MySQL, usualmente localhost en XAMPP
$usuario = "root";        // Usuario MySQL (por defecto en XAMPP es root)
$password = "";            // Contraseña MySQL (vacía en XAMPP por defecto)
$basedatos = "pixelpro";  // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($host, $usuario, $password, $basedatos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer codificación
$conexion->set_charset("utf8mb4");

// Listo para usar $conexion en otros scripts
?>