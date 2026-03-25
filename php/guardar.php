<?php
// Incluimos la conexión
include "conexion.php";

// Recibir datos del formulario (enviados vía fetch desde JS)
$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'];
$correo = $data['correo'];
$telefono = $data['telefono'];
$servicio = $data['servicio'];
$mensaje = $data['mensaje'];

// Preparar e insertar en la base de datos
$stmt = $conexion->prepare("INSERT INTO pedidos (nombre, correo, telefono, servicio, mensaje) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nombre, $correo, $telefono, $servicio, $mensaje);
$stmt->execute();
$stmt->close();
$conexion->close();

// Retornar respuesta para JS
echo json_encode(["status"=>"success"]);
?>