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
function contarEstado($conexion, $estado){
    return $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='$estado'")->fetch_assoc()['c'];
}

$p1 = contarEstado($conexion, "Pendiente");
$p2 = contarEstado($conexion, "Impreso");
$p3 = contarEstado($conexion, "Entregado");

$result = $conexion->query("SELECT * FROM pedidos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard PixelPro</title>
<style>
body{font-family:Montserrat;background:#f5f5f5;margin:0;padding:0;}
.cards{display:flex;justify-content:space-around;margin:20px 0;}
.card{padding:15px;border-radius:10px;color:#fff;width:150px;text-align:center;}
.p1{background:#2196F3;}
.p2{background:#FF1493;}
.p3{background:#00BCD4;}
table{width:95%;margin:auto;background:#fff;border-collapse:collapse;}
th{background:#171B26;color:#fff;}
th,td{padding:10px;text-align:center;}
button, select{
background:linear-gradient(45deg,#00BFFF,#FF1493);
color:#fff;border:none;padding:5px;cursor:pointer;
border-radius:5px;
}
button:hover, select:hover{opacity:0.9;}
</style>
</head>
<body>

<h1 style="text-align:center;">🚀 PixelPro Dashboard</h1>

<div class="cards">
<div class="card p1">Pendientes: <span id="contPendiente"><?= $p1 ?></span></div>
<div class="card p2">Impresos: <span id="contImpreso"><?= $p2 ?></span></div>
<div class="card p3">Entregados: <span id="contEntregado"><?= $p3 ?></span></div>
</div>

<table id="tablaPedidos">
<tr>
<th>ID</th><th>Nombre</th><th>Archivo</th><th>Estado</th><th>Acciones</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr id="pedido-<?= $row['id'] ?>">
<td><?= $row['id'] ?></td>
<td><?= $row['nombre'] ?></td>

<td>
<?php if($row['archivo']): ?>
<a href="<?= $row['archivo'] ?>" target="_blank">📄 Ver</a>
<?php endif; ?>
</td>

<td>
<select onchange="cambiarEstado(<?= $row['id'] ?>, this.value)">
<option <?= $row['estado']=="Pendiente"?"selected":"" ?>>Pendiente</option>
<option <?= $row['estado']=="Impreso"?"selected":"" ?>>Impreso</option>
<option <?= $row['estado']=="Entregado"?"selected":"" ?>>Entregado</option>
</select>
</td>

<td>
<button onclick="eliminarPedido(<?= $row['id'] ?>)">Eliminar</button>
</td>
</tr>
<?php endwhile; ?>
</table>

<script>
// Eliminar pedido con AJAX
function eliminarPedido(id){
    if(confirm("¿Deseas eliminar este pedido?")){
        fetch("php/eliminar.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "id=" + id
        })
        .then(res => res.text())
        .then(res => {
            document.getElementById("pedido-" + id).remove();
            actualizarContadores();
        })
        .catch(err => alert("Error al eliminar: " + err));
    }
}

// Cambiar estado con AJAX
function cambiarEstado(id, estado){
    fetch("php/cambiarEstado.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "id=" + id + "&estado=" + encodeURIComponent(estado)
    })
    .then(res => res.text())
    .then(res => {
        actualizarContadores();
        if(estado == "Impreso"){
            fetch("php/whatsapp.php?id=" + id) // notificación WhatsApp
        }
    })
    .catch(err => alert("Error al cambiar estado: " + err));
}

// Actualizar contadores
function actualizarContadores(){
    fetch("php/contadores.php")
    .then(res => res.json())
    .then(data => {
        document.getElementById("contPendiente").innerText = data.Pendiente;
        document.getElementById("contImpreso").innerText = data.Impreso;
        document.getElementById("contEntregado").innerText = data.Entregado;
    });
}
</script>
</body>
</html>