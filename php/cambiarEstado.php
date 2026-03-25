<?php
$conexion = new mysqli("localhost","root","","pixelpro");
$id = $_POST['id'];
$estado = $_POST['estado'];

// Actualizar estado
$stmt = $conexion->prepare("UPDATE pedidos SET estado=? WHERE id=?");
$stmt->bind_param("si",$estado,$id);
$stmt->execute();
$stmt->close();

// Enviar correo al cliente si el pedido fue impreso
if($estado=="Impreso"){
    $pedido = $conexion->query("SELECT correo, nombre FROM pedidos WHERE id=$id")->fetch_assoc();
    $to = $pedido['correo'];
    $subject = "PixelPro - Tu pedido ha sido impreso";
    $message = "Hola ".$pedido['nombre']."!\n\nTu pedido ya fue impreso y está listo para entrega. Gracias por confiar en PixelPro!";
    $headers = "From: pixelproinpresionesdigitales@gmail.com";
    mail($to,$subject,$message,$headers);
}

header("Location: ../pizarra.php");
?>