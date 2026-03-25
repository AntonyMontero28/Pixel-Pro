<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'];
$correo = $data['correo'];
$telefono = $data['telefono'];
$servicio = $data['servicio'];
$mensaje = $data['mensaje'];

$conexion = new mysqli("localhost","root","","pixelpro");

if($conexion->connect_error){
    die("Error: ".$conexion->connect_error);
}

$stmt = $conexion->prepare("INSERT INTO pedidos (nombre, correo, telefono, servicio, mensaje) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss",$nombre,$correo,$telefono,$servicio,$mensaje);
$stmt->execute();
$stmt->close();
$conexion->close();

echo json_encode(["status"=>"success"]);
?>