<?php
include "conexion.php";
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
select{padding:5px;}
body{
  margin:0;
  font-family:'Montserrat';
  background:#fff;
  overflow:hidden;
}

/* Fondo CMYK */
.shape {
  position: fixed;
  border-radius: 50%;
  animation: float 15s infinite ease-in-out alternate;
  opacity: 0.7;
  z-index: -1;
}
.shape.cyan { background: #00BCD4; width: 300px; height: 300px; top: 10%; left: 5%; }
.shape.magenta { background: #E91E63; width: 350px; height: 350px; bottom: 5%; right: 15%; }
.shape.yellow { background: #FFEB3B; width: 250px; height: 250px; top: 50%; left: 60%; }

@keyframes float {
  0% { transform: translateY(0);}
  50% { transform: translateY(-40px);}
  100% { transform: translateY(0);}
}

table{
  width:95%;
  margin:40px auto;
  border-collapse:collapse;
  background:#fff;
  border-radius:20px;
  overflow:hidden;
  box-shadow:0 20px 60px rgba(0,0,0,0.2);
}

th{
  background:#171B26;
  color:#fff;
}

th,td{
  padding:12px;
  text-align:center;
}

select{
  border-radius:10px;
  padding:5px;
}
</style>
</head>
<body>
    <div class="shape cyan"></div>
<div class="shape magenta"></div>
<div class="shape yellow"></div>
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
<form method="POST" action="cambiarEstado.php">
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