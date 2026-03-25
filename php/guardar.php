<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "pixelpro");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];
    $servicio = $_POST["servicio"];
    $mensaje = $_POST["mensaje"];

    // Insertar en la base de datos usando prepared statement
    $sql = "INSERT INTO pedidos (nombre, correo, telefono, servicio, mensaje)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $correo, $telefono, $servicio, $mensaje);

    if ($stmt->execute()) {
        echo "Pedido agregado correctamente. <a href='pizarra.php'>Ver Pizarra</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conexion->close();




