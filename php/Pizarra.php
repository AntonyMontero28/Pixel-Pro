<?php
session_start();

// 🔌 CONEXIÓN
$conexion = new mysqli("localhost", "root", "", "pixelpro");
if ($conexion->connect_error) {
    die("Error de conexión");
}

// 🔐 LOGIN
if(isset($_POST['login'])){
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    if($usuario == "admin" && $clave == "1234"){
        $_SESSION['login'] = true;
    } else {
        $error = "❌ Datos incorrectos";
    }
}

// 🔒 PROTEGER
if(!isset($_SESSION['login'])){
?>
<!DOCTYPE html>
<html>
<head>
<title>Login PixelPro</title>
<link rel="stylesheet" href="css/Pe.css">
</head>
<body>

<div class="pedido-container">
<h2>Login PixelPro</h2>

<form method="POST">
<input type="text" name="usuario" placeholder="Usuario" required>
<input type="password" name="clave" placeholder="Contraseña" required>
<button name="login">Entrar</button>
</form>

<?php if(isset($error)) echo $error; ?>

</div>
</body>
</html>
<?php
exit;
}

// 🗑️ ELIMINAR
if(isset($_POST['eliminar'])){
    $id = $_POST['id'];
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

    // 📲 WhatsApp cliente
    if($estado == "Impreso"){
        $res = $conexion->query("SELECT telefono, nombre FROM pedidos WHERE id=$id");
        $p = $res->fetch_assoc();

        $mensaje = "Hola ".$p['nombre']." 👋, tu pedido ya está IMPRESO ✅ en PixelPro.";

        echo "<script>window.open('https://wa.me/1".$p['telefono']."?text=".urlencode($mensaje)."','_blank');</script>";
    }
}

// 📊 CONTADORES
$p1 = $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='Pendiente'")->fetch_assoc()['c'];
$p2 = $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='Impreso'")->fetch_assoc()['c'];
$p3 = $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='Entregado'")->fetch_assoc()['c'];

$result = $conexion->query("SELECT * FROM pedidos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>PixelPro Dashboard</title>

<style>
body{
  font-family:Montserrat;
  margin:0;
  background:#f5f5f5;
}

h1{text-align:center;}

.cards{
  display:flex;
  justify-content:space-around;
  margin:20px;
}

.card{
  padding:15px;
  border-radius:15px;
  color:#fff;
  font-weight:bold;
}

.p1{background:#2196F3;}
.p2{background:#FF1493;}
.p3{background:#00BCD4;}

table{
  width:95%;
  margin:auto;
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
  padding:10px;
  text-align:center;
}

button{
  background:linear-gradient(45deg,#00BFFF,#FF1493);
  color:#fff;
  border:none;
  padding:6px;
  border-radius:8px;
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
<th>ID</th><th>Nombre</th><th>Teléfono</th><th>Archivo</th><th>Estado</th><th>Acciones</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['nombre'] ?></td>
<td><?= $row['telefono'] ?></td>

<td>
<a href="<?= $row['archivo'] ?>" target="_blank">Ver</a>
</td>

<td>
<form method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<select name="estado" onchange="this.form.submit()">
<option value="Pendiente" <?= $row['estado']=='Pendiente'?'selected':'' ?>>Pendiente</option>
<option value="Impreso" <?= $row['estado']=='Impreso'?'selected':'' ?>>Impreso</option>
<option value="Entregado" <?= $row['estado']=='Entregado'?'selected':'' ?>>Entregado</option>
</select>
<input type="hidden" name="cambiar">
</form>
</td>

<td>
<form method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<button name="eliminar">Eliminar</button>
</form>
</td>

</tr>
<?php endwhile; ?>
</table>

<audio id="sonido" src="https://www.soundjay.com/buttons/sounds/button-3.mp3"></audio>

<script>
let prev = 0;

setInterval(()=>{
 fetch("")
 .then(()=>location.reload())
},5000);
</script>

</body>
</html>  