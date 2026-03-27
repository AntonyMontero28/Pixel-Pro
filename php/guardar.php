<?php
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];
    $servicio = $_POST["servicio"];
    $mensaje = $_POST["mensaje"];

    // Subir archivo
    $archivoNombre = $_FILES["archivo"]["name"];
    $ruta = "../uploads/" . $archivoNombre;
    move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta);

    // Guardar en BD
    $stmt = $conexion->prepare("INSERT INTO pedidos (nombre, correo, telefono, servicio, mensaje, archivo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $correo, $telefono, $servicio, $mensaje, $ruta);
    $stmt->execute();

    // WhatsApp empresa
    $mensajeWP = "Nuevo pedido PixelPro 🚀
Nombre: $nombre
Tel: $telefono
Servicio: $servicio
Detalles: $mensaje";

    header("Location: https://wa.me/18294309250?text=" . urlencode($mensajeWP));
    exit;
}
?>

