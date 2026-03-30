<?php
// =======================================
// 🗑️ ELIMINAR PEDIDO PIXELPRO PRO
// =======================================

// Conexión
$conexion = new mysqli("localhost","root","","pixelpro");

// Verificar conexión
if($conexion->connect_error){
    die("Error de conexión");
}

// Verificar si llega el ID
if(isset($_POST['id'])){

    $id = intval($_POST['id']);

    // 1️⃣ Obtener archivo antes de borrar
    $stmt = $conexion->prepare("SELECT archivo FROM pedidos WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $pedido = $res->fetch_assoc();

        // 2️⃣ Eliminar archivo físico si existe
        if(!empty($pedido['archivo'])){
            $ruta = "../" . $pedido['archivo'];

            if(file_exists($ruta)){
                unlink($ruta);
            }
        }

        // 3️⃣ Eliminar de la base de datos
        $stmt2 = $conexion->prepare("DELETE FROM pedidos WHERE id=?");
        $stmt2->bind_param("i",$id);

        if($stmt2->execute()){
            echo "ok"; // importante para JS
        } else {
            echo "error_db";
        }

        $stmt2->close();

    } else {
        echo "no_existe";
    }

    $stmt->close();

} else {
    echo "no_id";
}

$conexion->close();
?>