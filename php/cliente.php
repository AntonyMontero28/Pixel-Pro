<?php
session_start();
$host="localhost"; $user="root"; $pass=""; $db="pixelpro";
$conexion = new mysqli($host,$user,$pass,$db);
if($conexion->connect_error) die("Error DB: ".$conexion->connect_error);
$conexion->set_charset("utf8mb4");

// ===== Registro de cliente =====
if(isset($_POST['action']) && $_POST['action']=='registro'){
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, correo, telefono, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss",$nombre,$correo,$telefono,$pass);
    if($stmt->execute()){
        echo json_encode(["success"=>true,"message"=>"Registro exitoso. Inicia sesión"]);
    } else echo json_encode(["success"=>false,"message"=>$stmt->error]);
    $stmt->close();
    exit;
}

// ===== Login de cliente =====
if(isset($_POST['action']) && $_POST['action']=='login'){
    $correo = $_POST['correo'];
    $pass = $_POST['password'];

    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE correo=?");
    $stmt->bind_param("s",$correo);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows>0){
        $cliente = $res->fetch_assoc();
        if(password_verify($pass,$cliente['password'])){
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['cliente_nombre'] = $cliente['nombre'];
            echo json_encode(["success"=>true]);
        } else echo json_encode(["success"=>false,"message"=>"Contraseña incorrecta"]);
    } else echo json_encode(["success"=>false,"message"=>"Usuario no encontrado"]);
    $stmt->close();
    exit;
}

// ===== Logout =====
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: clientes.php");
    exit;
}
?>