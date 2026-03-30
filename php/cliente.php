<?php
session_start();
$host="localhost"; $user="root"; $pass=""; $db="pixelpro";
$conexion = new mysqli($host,$user,$pass,$db);
if($conexion->connect_error) die("Error DB: ".$conexion->connect_error);
$conexion->set_charset("utf8mb4");

// ===== Registro de cliente =====
if(isset($_POST['action']) && $_POST['action']=='registro'){
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, correo, telefono, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss",$nombre,$correo,$telefono,$pass);
    if($stmt->execute()){
        echo "<p style='color:green;'>✅ Registro exitoso. <a href='?login'>Inicia sesión</a></p>";
    } else echo "<p style='color:red;'>❌ Error: ".$stmt->error."</p>";
    $stmt->close();
}

// ===== Login de cliente =====
if(isset($_POST['action']) && $_POST['action']=='login'){
    $correo = $_POST['correo'];
    $pass = $_POST['password'];

    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE correo=?");
    $stmt->bind_param("s",$correo);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows>0){
        $cliente = $res->fetch_assoc();
        if(password_verify($pass,$cliente['password'])){
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['cliente_nombre'] = $cliente['nombre'];
            header("Location: ?panel"); exit;
        } else echo "<p style='color:red;'>❌ Contraseña incorrecta</p>";
    } else echo "<p style='color:red;'>❌ Usuario no encontrado</p>";
    $stmt->close();
}

// ===== Logout =====
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: ?login");
    exit;
}

// ===== Nuevo pedido =====
if(isset($_POST['action']) && $_POST['action']=='pedido' && isset($_SESSION['cliente_id'])){
    $cliente_id = $_SESSION['cliente_id'];
    $servicio = $_POST['servicio'];
    $mensaje = $_POST['mensaje'];
    $archivo = "";

    if(isset($_FILES['archivo']) && $_FILES['archivo']['error']==0){
        $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
        $archivo = "uploads/".time()."_".rand(100,999).".".$ext;
        move_uploaded_file($_FILES['archivo']['tmp_name'],$archivo);
    }

    $stmt = $conexion->prepare("INSERT INTO pedidos (cliente_id, archivo, servicio, mensaje) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss",$cliente_id,$archivo,$servicio,$mensaje);
    if($stmt->execute()){
        // WhatsApp: enviar notificación a la empresa
        $telefono_empresa = "1111111111"; // Reemplazar por el número real
        $mensaje_ws = "Nuevo pedido de $cliente_id, servicio: $servicio";
        // header("Location: https://wa.me/$telefono_empresa?text=".urlencode($mensaje_ws));
        echo "<p style='color:green;'>✅ Pedido enviado</p>";
    } else echo "<p style='color:red;'>❌ Error: ".$stmt->error."</p>";
    $stmt->close();
}

// ===== Mostrar panel =====
if(isset($_GET['panel']) && isset($_SESSION['cliente_id'])){
    $cliente_id = $_SESSION['cliente_id'];
    $pedidos = $conexion->query("SELECT * FROM pedidos WHERE cliente_id=$cliente_id ORDER BY fecha DESC");
?>

<h1>Bienvenido, <?= $_SESSION['cliente_nombre'] ?> | <a href='?logout'>Cerrar sesión</a></h1>
<h2>Realizar Pedido</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="pedido">
    Servicio:
    <select name="servicio" required>
        <option value="">Selecciona</option>
        <option>Fotográfica</option>
        <option>Blanco y negro</option>
        <option>Color</option>
        <option>Máximo color</option>
    </select><br>
    Archivo: <input type="file" name="archivo"><br>
    Mensaje: <textarea name="mensaje"></textarea><br>
    <input type="submit" value="Enviar Pedido">
</form>

<h2>Mis Pedidos</h2>
<table border=1 cellpadding=10>
<tr><th>ID</th><th>Servicio</th><th>Archivo</th><th>Mensaje</th><th>Estado</th><th>Fecha</th></tr>
<?php while($p=$pedidos->fetch_assoc()): ?>
<tr>
<td><?= $p['id'] ?></td>
<td><?= $p['servicio'] ?></td>
<td><?= $p['archivo'] ? "<a href='".$p['archivo']."' target='_blank'>Ver</a>":"" ?></td>
<td><?= $p['mensaje'] ?></td>
<td><?= $p['estado'] ?></td>
<td><?= $p['fecha'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php
    exit;
}

// ===== Login / Registro =====
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login / Registro PixelPro</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
body{font-family:'Montserrat';margin:0;background:#fff;overflow:hidden;}
.shape{position:fixed;border-radius:50%;opacity:0.7;animation:float 15s infinite alternate;z-index:-1;}
.shape.cyan{background:#00BCD4;width:300px;height:300px;top:10%;left:5%;}
.shape.magenta{background:#E91E63;width:350px;height:350px;bottom:5%;right:15%;}
.shape.yellow{background:#FFEB3B;width:250px;height:250px;top:50%;left:60%;}
@keyframes float{0%{transform:translateY(0);}50%{transform:translateY(-40px);}100%{transform:translateY(0);}}

form{max-width:400px;margin:100px auto;padding:30px;background:rgba(255,255,255,0.95);border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,0.2);}
h2{text-align:center;margin-bottom:20px;}
input, select, textarea{width:90%;padding:12px;margin:10px 0;border-radius:10px;border:2px solid #171B26;outline:none;transition:0.3s;}
input:focus,textarea:focus,select:focus{border-color:#FF1493;box-shadow:0 0 10px #FF1493;}
button,input[type=submit]{width:100%;padding:12px;border:none;border-radius:30px;background:linear-gradient(45deg,#00BFFF,#FF1493);color:#fff;font-weight:bold;cursor:pointer;transition:0.3s;}
button:hover,input[type=submit]:hover{transform:scale(1.05);box-shadow:0 10px 30px rgba(0,0,0,0.3);}
a{display:block;text-align:center;margin:5px;}
</style>
</head>
<body>
<div class="shape cyan"></div>
<div class="shape magenta"></div>
<div class="shape yellow"></div>

<h2>Login Cliente PixelPro</h2>
<form method="POST">
<input type="hidden" name="action" value="login">
<label>Correo</label>
<input type="email" name="correo" required>
<label>Contraseña</label>
<input type="password" name="password" required>
<input type="submit" value="Iniciar Sesión">
<a href="?registro">¿No tienes cuenta? Regístrate</a>
<a href="#">Política de Privacidad</a>
<a href="#">Términos y Condiciones</a>
</form>

<?php if(isset($_GET['registro'])): ?>
<h2>Registro Cliente PixelPro</h2>
<form method="POST">
<input type="hidden" name="action" value="registro">
<label>Nombre</label>
<input type="text" name="nombre" required>
<label>Correo</label>
<input type="email" name="correo" required>
<label>Teléfono</label>
<input type="tel" name="telefono" required>
<label>Contraseña</label>
<input type="password" name="password" required>
<input type="submit" value="Registrar">
<a href="?login">Ya tengo cuenta</a>
</form>
<?php endif; ?>
</body>
</html>