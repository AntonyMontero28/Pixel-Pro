<?php
$conexion = new mysqli("localhost","root","","pixelpro");
$result = $conexion->query("SELECT * FROM pedidos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pizarra de Pedidos - PixelPro</title>
<link rel="stylesheet" href="css/Pe.css">
<style>
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #000;padding:10px;text-align:center;}
button{padding:5px 10px;margin:2px;cursor:pointer;}
</style>
</head>
<body>
<h1>Pizarra de Pedidos - PixelPro</h1>
<table>
<tr>
<th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Servicio</th><th>Mensaje</th><th>Estado</th><th>Acciones</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['nombre'] ?></td>
<td><?= $row['correo'] ?></td>
<td><?= $row['telefono'] ?></td>
<td><?= $row['servicio'] ?></td>
<td><?= $row['mensaje'] ?></td>
<td id="estado<?= $row['id'] ?>"><?= $row['estado'] ?></td>
<td>
<form method="POST" action="php/cambiarEstado.php">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<select name="estado" onchange="this.form.submit()">
<option value="Pendiente" <?= $row['estado']=='Pendiente'?'selected':'' ?>>Pendiente</option>
<option value="Impreso" <?= $row['estado']=='Impreso'?'selected':'' ?>>Impreso</option>
<option value="Entregado" <?= $row['estado']=='Entregado'?'selected':'' ?>>Entregado</option>
</select>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>