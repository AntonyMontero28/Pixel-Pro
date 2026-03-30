<?php
session_start();
$conexion = new mysqli("localhost","root","","pixelpro");

// LOGIN SIMPLE
if(!isset($_SESSION['login'])){
    if(isset($_POST['user'])){
        if($_POST['user']=="admin" && $_POST['pass']=="1234"){
            $_SESSION['login']=true;
        } else {
            $error="Datos incorrectos";
        }
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

// CONTADORES
$p1 = $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='Pendiente'")->fetch_assoc()['c'];
$p2 = $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='Impreso'")->fetch_assoc()['c'];
$p3 = $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='Entregado'")->fetch_assoc()['c'];

$result = $conexion->query("SELECT * FROM pedidos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard PixelPro</title>

<style>
body{font-family:Montserrat;background:#f5f5f5;}

.cards{display:flex;justify-content:space-around;}
.card{padding:15px;border-radius:10px;color:#fff;}
.p1{background:#2196F3;}
.p2{background:#FF1493;}
.p3{background:#00BCD4;}

table{
width:95%;margin:auto;background:#fff;
border-collapse:collapse;
}

th{background:#171B26;color:#fff;}
th,td{padding:10px;text-align:center;}

button{
background:linear-gradient(45deg,#00BFFF,#FF1493);
color:#fff;border:none;padding:5px;
cursor:pointer;
}
</style>
</head>

<body>

<h1>🚀 PixelPro Dashboard</h1>

<div class="cards">
<div class="card p1">Pendientes: <?= $p1 ?></div>
<div class="card p2">Impresos: <?= $p2 ?></div>
<div class="card p3">Entregados: <?= $p3 ?></div>
</div>

<table>
<tr>
<th>ID</th><th>Nombre</th><th>Archivo</th><th>Estado</th><th>Acciones</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['nombre'] ?></td>

<td>
<?php if($row['archivo']): ?>
<a href="<?= $row['archivo'] ?>" target="_blank">📄 Ver</a>
<?php endif; ?>
</td>

<td><?= $row['estado'] ?></td>

<td>

<form method="POST" action="php/cambiar.php">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<select name="estado" onchange="this.form.submit()">
<option <?= $row['estado']=="Pendiente"?"selected":"" ?>>Pendiente</option>
<option <?= $row['estado']=="Impreso"?"selected":"" ?>>Impreso</option>
<option <?= $row['estado']=="Entregado"?"selected":"" ?>>Entregado</option>
</select>
</form>

<form method="POST" action="php/eliminar.php">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<button>Eliminar</button>
</form>

<?php
$conexion = new mysqli("localhost","root","","pixelpro");

$id = $_POST['id'];

$stmt = $conexion->prepare("DELETE FROM pedidos WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

header("Location: ../pizarra.php");
?>
</td>

</tr>
<?php endwhile; ?>
</table>

</body>
</html>