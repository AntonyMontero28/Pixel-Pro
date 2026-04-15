<?php
$conexion = new mysqli("localhost", "root", "", "pixelpro");

$nombre = $_POST["nombre"];
$correo = $_POST["correo"];
$telefono = $_POST["telefono"];
$servicio = $_POST["servicio"];
$mensaje = $_POST["mensaje"];

$archivo = "";

// Guardar archivo
if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0){
    $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
    $archivo = "uploads/" . time() . "_" . rand(100,999) . "." . $ext;
    move_uploaded_file($_FILES['archivo']['tmp_name'], "../".$archivo);
}

// Insertar en BD
$stmt = $conexion->prepare("INSERT INTO pedidos (nombre, correo, telefono, servicio, mensaje, archivo) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nombre, $correo, $telefono, $servicio, $mensaje, $archivo);
$stmt->execute();

// WhatsApp empresa
$mensaje_ws = "Nuevo pedido de $nombre - $servicio";
header("Location: https://wa.me/18498752651?text=" . urlencode($mensaje_ws));
exit;
?>