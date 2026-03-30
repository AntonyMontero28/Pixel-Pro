<?php
session_start();
$conexion = new mysqli("localhost","root","","pixelpro");
if($conexion->connect_error) die("Error DB: ".$conexion->connect_error);

// 📌 LOGIN ADMIN SIMPLE
if(!isset($_SESSION['login'])){
    if(isset($_POST['user'])){
        if($_POST['user']=="admin" && $_POST['pass']=="1234"){
            $_SESSION['login']=true;
        } else $error="❌ Datos incorrectos";
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Login PixelPro</title>
<style>
body{font-family:Montserrat;background:#f5f5f5;margin:0;padding:0;text-align:center;}
input,button{padding:10px;margin:5px;border-radius:5px;border:1px solid #171B26;}
button{background:linear-gradient(45deg,#00BFFF,#FF1493);color:#fff;cursor:pointer;}
</style>
</head>
<body>
<h2>Login PixelPro</h2>
<form method="POST">
<input name="user" placeholder="Usuario"><br>
<input type="password" name="pass" placeholder="Clave"><br>
<button>Entrar</button>
<?= isset($error)?$error:"" ?>
</form>
</body>
</html>
<?php exit; }

// 💾 REGISTRAR PEDIDO (con archivo)
if(isset($_POST['guardar'])){
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $servicio = $_POST['servicio'];
    $mensaje = $_POST['mensaje'];
    $archivo = "";

    if(isset($_FILES['archivo']) && $_FILES['archivo']['error']==0){
        if(!is_dir("uploads")) mkdir("uploads");
        $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
        $archivo = "uploads/".time()."_".rand(100,999).".".$ext;
        move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo);
    }

    $stmt = $conexion->prepare("INSERT INTO pedidos (nombre, correo, telefono, servicio, mensaje, archivo, estado, fecha) VALUES (?,?,?,?,?,?, 'Pendiente', NOW())");
    $stmt->bind_param("ssssss",$nombre,$correo,$telefono,$servicio,$mensaje,$archivo);
    $stmt->execute();

    // WhatsApp a la empresa
    $telefono_empresa = "8294309250";
    $mensaje_ws = "Nuevo pedido de $nombre\nServicio: $servicio";
    echo "<script>window.open('https://wa.me/$telefono_empresa?text=".urlencode($mensaje_ws)."','_blank');</script>";
}

// 💾 ELIMINAR PEDIDO
if(isset($_POST['eliminar'])){
    $id = $_POST['id'];
    // Borrar archivo si existe
    $res = $conexion->query("SELECT archivo FROM pedidos WHERE id=$id");
    $row = $res->fetch_assoc();
    if($row['archivo'] && file_exists($row['archivo'])) unlink($row['archivo']);

    $stmt = $conexion->prepare("DELETE FROM pedidos WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
}

// 🔄 CAMBIAR ESTADO
if(isset($_POST['cambiar'])){
    $id = $_POST['id'];
    $estado = $_POST['estado'];
    $stmt = $conexion->prepare("UPDATE pedidos SET estado=? WHERE id=?");
    $stmt->bind_param("si",$estado,$id);
    $stmt->execute();

    if($estado=="Impreso"){
        $res = $conexion->query("SELECT nombre, servicio FROM pedidos WHERE id=$id");
        $p = $res->fetch_assoc();
        $mensaje = "Nuevo pedido de ".$p['nombre']."\nServicio: ".$p['servicio'];
        $telefono_empresa = "8294309250";
        echo "<script>window.open('https://wa.me/$telefono_empresa?text=".urlencode($mensaje)."','_blank');</script>";
    }
}

// 📊 CONTADORES
function contarEstado($conexion, $estado){
    return $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='$estado'")->fetch_assoc()['c'];
}
$p1 = contarEstado($conexion,"Pendiente");
$p2 = contarEstado($conexion,"Impreso");
$p3 = contarEstado($conexion,"Entregado");

$result = $conexion->query("SELECT * FROM pedidos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PixelPro Dashboard</title>
<style>
body{font-family:Montserrat;margin:0;padding:0;background:#f5f5f5;}
.cards{display:flex;justify-content:space-around;margin:20px 0;}
.card{padding:15px;border-radius:10px;color:#fff;width:150px;text-align:center;}
.p1{background:#2196F3;}
.p2{background:#FF1493;}
.p3{background:#00BCD4;}
table{width:95%;margin:auto;background:#fff;border-collapse:collapse;border-radius:10px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.1);}
th{background:#171B26;color:#fff;}
th,td{padding:10px;text-align:center;}
button, select{background:linear-gradient(45deg,#00BFFF,#FF1493);color:#fff;border:none;padding:5px;cursor:pointer;border-radius:5px;}
button:hover, select:hover{opacity:0.9;}
a{color:#FF1493;text-decoration:none;}
form{margin:0;}
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
<?php else: ?>
-
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

<h2 style="text-align:center;margin-top:40px;">📥 Registrar Pedido Manual</h2>
<form method="POST" enctype="multipart/form-data" style="width:300px;margin:20px auto;">
<input type="text" name="nombre" placeholder="Nombre" required><br>
<input type="email" name="correo" placeholder="Correo" required><br>
<input type="tel" name="telefono" placeholder="Teléfono" required><br>
<select name="servicio" required>
<option value="">Servicio</option>
<option>Fotográfica</option>
<option>Blanco y negro</option>
<option>Color</option>
<option>Máximo color</option>
</select><br>
<textarea name="mensaje" placeholder="Mensaje"></textarea><br>
<input type="file" name="archivo"><br><br>
<button name="guardar">Guardar Pedido</button>
</form>

<script>
// Eliminar pedido
function eliminarPedido(id){
    if(confirm("¿Deseas eliminar este pedido?")){
        fetch("", {
            method:"POST",
            headers:{"Content-Type":"application/x-www-form-urlencoded"},
            body:"eliminar=1&id="+id
        }).then(()=>document.getElementById("pedido-"+id).remove())
          .catch(e=>alert("Error al eliminar: "+e));
    }
}

// Cambiar estado
function cambiarEstado(id, estado){
    fetch("", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"cambiar=1&id="+id+"&estado="+encodeURIComponent(estado)
    }).then(()=>location.reload())
      .catch(e=>alert("Error al cambiar estado: "+e));
}
</script>
</body>
</html>