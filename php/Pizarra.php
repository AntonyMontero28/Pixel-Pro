<?php
session_start();
$conexion = new mysqli("localhost","root","","pixelpro");
if($conexion->connect_error) die("Error DB: ".$conexion->connect_error);

// LOGIN ADMIN
if(!isset($_SESSION['login'])){
    if(isset($_POST['user'])){
        if($_POST['user']=="admin" && $_POST['pass']=="1234"){
            $_SESSION['login']=true;
        } else $error="❌ Datos incorrectos";
    }
?>
<form method="POST" style="text-align:center;margin-top:100px;">
<h2>Login PixelPro</h2>
<input name="user" placeholder="Usuario"><br><br>
<input type="password" name="pass" placeholder="Clave"><br><br>
<button>Entrar</button>
<?= isset($error)?$error:"" ?>
</form>
<?php exit; }

// ELIMINAR PEDIDO
if(isset($_POST['eliminar'])){
    $id = $_POST['id'];
    $res = $conexion->query("SELECT archivo FROM pedidos WHERE id=$id");
    $row = $res->fetch_assoc();
    if($row['archivo'] && file_exists($row['archivo'])) unlink($row['archivo']); // borrar archivo
    $stmt = $conexion->prepare("DELETE FROM pedidos WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
}

// CAMBIAR ESTADO
if(isset($_POST['cambiar'])){
    $id = $_POST['id'];
    $estado = $_POST['estado'];
    $stmt = $conexion->prepare("UPDATE pedidos SET estado=? WHERE id=?");
    $stmt->bind_param("si",$estado,$id);
    $stmt->execute();
}

// CONTADORES
function contarEstado($conexion,$estado){
    return $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='$estado'")->fetch_assoc()['c'];
}
$p1=contarEstado($conexion,"Pendiente");
$p2=contarEstado($conexion,"Impreso");
$p3=contarEstado($conexion,"Entregado");

$result = $conexion->query("SELECT * FROM pedidos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PixelPro Dashboard</title>
<style>
body{font-family:Montserrat;background:#f5f5f5;margin:0;padding:0;}
.cards{display:flex;justify-content:space-around;margin:20px;}
.card{padding:15px;border-radius:10px;color:#fff;width:150px;text-align:center;}
.p1{background:#2196F3;}
.p2{background:#FF1493;}
.p3{background:#00BCD4;}
table{width:95%;margin:auto;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 10px 20px rgba(0,0,0,0.1);}
th{background:#171B26;color:#fff;}
th,td{padding:10px;text-align:center;}
button,select{padding:5px;border:none;border-radius:5px;background:linear-gradient(45deg,#00BFFF,#FF1493);color:#fff;cursor:pointer;}
button:hover,select:hover{opacity:0.9;}
a{color:#FF1493;text-decoration:none;}
</style>
</head>
<body>

<h1 style="text-align:center;">🚀 PixelPro Dashboard</h1>

<div class="cards">
<div class="card p1">Pendientes: <?= $p1 ?></div>
<div class="card p2">Impresos: <?= $p2 ?></div>
<div class="card p3">Entregados: <?= $p3 ?></div>
</div>

<table>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Archivo</th>
<th>Estado</th>
<th>Acciones</th>
</tr>

<?php while($row=$result->fetch_assoc()): ?>
<tr id="pedido-<?= $row['id'] ?>">
<td><?= $row['id'] ?></td>
<td><?= $row['nombre'] ?></td>

<td>
<?php if($row['archivo'] && file_exists($row['archivo'])): ?>
<a href="<?= $row['archivo'] ?>" target="_blank">📄 Ver</a>
<?php else: ?>
-
<?php endif; ?>
</td>

<td>
<form method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<select name="estado" onchange="this.form.submit()">
<option <?= $row['estado']=="Pendiente"?"selected":"" ?>>Pendiente</option>
<option <?= $row['estado']=="Impreso"?"selected":"" ?>>Impreso</option>
<option <?= $row['estado']=="Entregado"?"selected":"" ?>>Entregado</option>
</select>
<input type="hidden" name="cambiar" value="1">
</form>
</td>

<td>
<form method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<button name="eliminar" onclick="return confirm('¿Deseas eliminar este pedido?')">Eliminar</button>
</form>
</td>

</tr>
<?php endwhile; ?>
</table>

</body>
</html>