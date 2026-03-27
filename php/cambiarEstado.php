<?php
include "conexion.php";

$id = $_POST['id'];
$estado = $_POST['estado'];

$stmt = $conexion->prepare("UPDATE pedidos SET estado=? WHERE id=?");
$stmt->bind_param("si", $estado, $id);
$stmt->execute();

if($estado == "Impreso"){
    $res = $conexion->query("SELECT telefono, nombre FROM pedidos WHERE id=$id");
    $p = $res->fetch_assoc();

    $mensaje = "Hola ".$p['nombre']." 👋, tu pedido ya está IMPRESO ✅ en PixelPro.";

    header("Location: https://wa.me/1".$p['telefono']."?text=".urlencode($mensaje));
    exit;
}

header("Location: ../pizarra.php");
?>