<?php
$conexion = new mysqli("localhost","root","","pixelpro");

// Datos del formulario
$nombre = $_POST["nombre"];
$correo = $_POST["correo"];
$telefono = $_POST["telefono"];
$servicio = $_POST["servicio"];
$mensaje = $_POST["mensaje"];

$archivo = "";

// 📁 Subir archivo
if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0){
    $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
    $archivo = "uploads/" . time() . "_" . rand(100,999) . "." . $ext;
    move
?>