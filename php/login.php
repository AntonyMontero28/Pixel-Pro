<?php
session_start();
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.html");
    exit;
}

$correo   = trim($_POST['correo'] ?? '');
$password = $_POST['password'] ?? '';

if (!$correo || !$password) {
    header("Location: ../login.html?error=1");
    exit;
}

$stmt = $conexion->prepare(
    "SELECT id, nombre, password FROM usuarios WHERE correo = ? AND activo = 1"
);
$stmt->bind_param('s', $correo);
$stmt->execute();
$stmt->bind_result($id, $nombre, $hash);
$stmt->fetch();
$stmt->close();

if ($id && password_verify($password, $hash)) {
    $_SESSION['usuario_id'] = $id;
    $_SESSION['usuario_nombre'] = $nombre;

    header("Location: ../index.html");
    exit;
} else {
    header("Location: ../login.html?error=1");
    exit;
}

$conexion->close();