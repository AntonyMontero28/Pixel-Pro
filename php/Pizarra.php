<?php
session_start();
$conexion = new mysqli("localhost","root","","pixelpro");

if($conexion->connect_error){
    die("Error de conexión: " . $conexion->connect_error);
}

/* =========================
   LOGIN SIMPLE
========================= */
if(!isset($_SESSION['login'])){
    if(isset($_POST['user'])){
        if($_POST['user']=="admin" && $_POST['pass']=="1234"){
            $_SESSION['login']=true;
            header("Location: pizarra.php");
            exit;
        } else {
            $error="Datos incorrectos";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login PixelPro</title>
<style>
body{
    font-family:Arial;
    background:#f5f5f5;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.login-box{
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,.1);
    width:320px;
    text-align:center;
}
input,button{
    width:80%;
    padding:12px;
    margin:8px 0;
    border-radius:8px;
}
button{
    background:#171B26;
    color:#fff;
    border:none;
    cursor:pointer;
}
.error{color:red;}
</style>
</head>
<body>

<div class="login-box">
    <h2>🔐 Login PixelPro</h2>
    <form method="POST">
        <input name="user" placeholder="Usuario" required>
        <input type="password" name="pass" placeholder="Clave" required>
        <button>Entrar</button>
    </form>
    <?php if(isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
</div>

</body>
</html>
<?php
exit;
}

/* =========================
   CONTADORES
========================= */
$p1 = $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='Pendiente'")->fetch_assoc()['c'];
$p2 = $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='Impreso'")->fetch_assoc()['c'];
$p3 = $conexion->query("SELECT COUNT(*) c FROM pedidos WHERE estado='Entregado'")->fetch_assoc()['c'];

$result = $conexion->query("SELECT * FROM pedidos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard PixelPro</title>

<style>
body{
    font-family:Montserrat,Arial;
    background:#f5f5f5;
    margin:0;
    padding:20px;
}
h1{
    text-align:center;
    color:#171B26;
}
.cards{
    display:flex;
    gap:20px;
    justify-content:center;
    margin-bottom:25px;
    flex-wrap:wrap;
}
.card{
    padding:18px 25px;
    border-radius:12px;
    color:#fff;
    font-weight:bold;
    min-width:180px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,.1);
}
.p1{background:#2196F3;}
.p2{background:#FF1493;}
.p3{background:#00BCD4;}

table{
    width:100%;
    background:#fff;
    border-collapse:collapse;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}
th{
    background:#171B26;
    color:#fff;
}
th,td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

select, button{
    padding:8px;
    border-radius:8px;
    border:none;
}

.btn-delete{
    background:#e53935;
    color:white;
    cursor:pointer;
}

.btn-logout{
    float:right;
    background:#171B26;
    color:#fff;
    padding:10px 15px;
    text-decoration:none;
    border-radius:8px;
    margin-bottom:20px;
}
</style>
</head>
<body>

<a class="btn-logout" href="logout.php">Cerrar sesión</a>

<h1>🚀 PixelPro Dashboard</h1>

<div class="cards">
    <div class="card p1">Pendientes: <?= $p1 ?></div>
    <div class="card p2">Impresos: <?= $p2 ?></div>
    <div class="card p3">Entregados: <?= $p3 ?></div>
</div>

<table>
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Correo</th>
    <th>Teléfono</th>
    <th>Servicio</th>
    <th>Detalles</th>
    <th>Archivo</th>
    <th>Estado</th>
    <th>Fecha</th>
    <th>Eliminar</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr id="pedido-<?= $row['id'] ?>">

    <td><?= $row['id'] ?></td>

    <td><?= htmlspecialchars($row['nombre']) ?></td>

    <td><?= htmlspecialchars($row['correo']) ?></td>

    <td><?= htmlspecialchars($row['telefono']) ?></td>

    <td><?= htmlspecialchars($row['servicio']) ?></td>

    <td><?= htmlspecialchars($row['mensaje']) ?></td>

    <td>
        <?php if(!empty($row['archivo'])): ?>
            <a href="../<?= $row['archivo'] ?>" target="_blank">📄 Ver Archivo</a>
        <?php else: ?>
            Sin archivo
        <?php endif; ?>
    </td>

    <td>
        <form method="POST" action="cambiarEstado.php">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <select name="estado" onchange="this.form.submit()">
                <option <?= $row['estado']=="Pendiente"?"selected":"" ?>>Pendiente</option>
                <option <?= $row['estado']=="Impreso"?"selected":"" ?>>Impreso</option>
                <option <?= $row['estado']=="Entregado"?"selected":"" ?>>Entregado</option>
            </select>
        </form>
    </td>

    <td><?= $row['fecha'] ?></td>

    <td>
        <button class="btn-delete" onclick="eliminarPedido(<?= $row['id'] ?>)">Eliminar</button>
    </td>

</tr>
<?php endwhile; ?>

</table>

<script>
function eliminarPedido(id){
    if(confirm("¿Deseas eliminar este pedido?")){
        fetch("eliminar.php",{
            method:"POST",
            headers:{
                "Content-Type":"application/x-www-form-urlencoded"
            },
            body:"id="+id
        })
        .then(res=>res.text())
        .then(res=>{
            if(res.trim()=="ok"){
                document.getElementById("pedido-"+id).remove();
            }else{
                alert("Error al eliminar: "+res);
            }
        })
        .catch(err=>{
            alert("Error de conexión");
        });
    }
}
</script>

</body>
</html>