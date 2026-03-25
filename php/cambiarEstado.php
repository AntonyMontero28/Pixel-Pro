<?php
include "conexion.php"; // Conexión a la base de datos

if(isset($_POST['id']) && isset($_POST['estado'])){
    $id = $_POST['id'];
    $estado = $_POST['estado'];

    // Actualizar estado usando prepared statement
    $stmt = $conexion->prepare("UPDATE pedidos SET estado=? WHERE id=?");
    $stmt->bind_param("si", $estado, $id);
    $stmt->execute();
    $stmt->close();

    // Enviar correo al cliente si el pedido fue impreso
    if($estado=="Impreso"){
        $stmt2 = $conexion->prepare("SELECT correo, nombre FROM pedidos WHERE id=?");
        $stmt2->bind_param("i",$id);
        $stmt2->execute();
        $pedido = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();

        // Nota: mail() solo funciona si tu servidor de correo está configurado
        $to = $pedido['correo'];
        $subject = "PixelPro - Tu pedido ha sido impreso";
        $message = "Hola ".$pedido['nombre']."!\n\nTu pedido ya fue impreso y está listo para entrega. Gracias por confiar en PixelPro!";
        $headers = "From: pixelproinpresionesdigitales@gmail.com";

        // mail($to,$subject,$message,$headers); // Descomenta si tienes servidor de correo
    }
}

// Redirigir de vuelta a la pizarra
header("Location: ../pizarra.php");
exit;
?>