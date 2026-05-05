<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.html");
    exit;
}

$nombre   = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo   = trim($_POST['correo'] ?? '');
$password = $_POST['password'] ?? '';

if (!$nombre || !$telefono || !$correo || !$password) {
    header("Location: ../login.html?error=1");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../login.html?error=1");
    exit;
}

if (strlen($password) < 6) {
    header("Location: ../login.html?error=1");
    exit;
}

// Verificar duplicado
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
$stmt->bind_param('s', $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: ../login.html?error=1");
    exit;
}

$stmt->close();

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conexion->prepare(
    "INSERT INTO usuarios (nombre, telefono, correo, password) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param('ssss', $nombre, $telefono, $correo, $hash);

if ($stmt->execute()) {
    header("Location: ../login.html?registro=1");
} else {
    header("Location: ../login.html?error=1");
}

$stmt->close();
$conexion->close();