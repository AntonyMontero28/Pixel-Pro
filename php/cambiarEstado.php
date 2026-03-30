<?php
$conexion = new mysqli("localhost","root","","pixelpro");

$id = $_POST['id'];
$estado = $_POST['estado'];

$stmt = $conexion->prepare("UPDATE pedidos SET estado=? WHERE id=?");
$stmt->bind_param("si",$estado,$id);
$stmt->execute();

// WhatsApp cliente
if($estado == "Impreso"){
    $res = $conexion->query("SELECT telefono,nombre FROM pedidos WHERE id=$id");
    $p = $res->fetch_assoc();

    $msg = "Hola ".$p['nombre']." tu pedido ya esta IMPRESO ✅";
    $url = "https://wa.me/1".$p['telefono']."?text=".urlencode($msg);

    // Abrir en otra pestaña usando JavaScript
    echo "<script>
        window.open('$url', '_blank');
        window.location.href = 'pizarra.php';
    </script>";
    exit;
}

header("Location: pizarra.php");
?>