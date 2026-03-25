<?php
include "conexion.php";

if(isset($_POST['id']) && isset($_POST['estado'])){
    $id = $_POST['id'];
    $estado = $_POST['estado'];

    $stmt = $conexion->prepare("UPDATE pedidos SET estado=? WHERE id=?");
    $stmt->bind_param("si", $estado, $id);
    $stmt->execute();
    $stmt->close();

    if($estado=="Impreso"){
        $stmt2 = $conexion->prepare("SELECT telefono, nombre FROM pedidos WHERE id=?");
        $stmt2->bind_param("i",$id);
        $stmt2->execute();
        $pedido = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();

        $telefono = $pedido['telefono'];
        $nombre = $pedido['nombre'];

        $mensaje = "Hola $nombre, tu pedido en PixelPro ya fue impreso ✅ y está listo para entrega.";

        // Redirige a WhatsApp
        header("Location: https://wa.me/1$telefono?text=" . urlencode($mensaje));
        exit;
    }
}

// Volver a la pizarra
header("Location: pizarra.php");
exit;
?>