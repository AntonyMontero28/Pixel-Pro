// Login
document.getElementById("loginForm").addEventListener("submit", function(e){
    e.preventDefault();
    let formData = new FormData(this);

    fetch("php/login.php", {method:"POST", body: formData})
    .then(r=>r.json())
    .then(data=>{
        if(data.success){
            alert("Login exitoso!");
            window.location.href = "panel_cliente.php"; // Página privada del cliente
        }else{
            alert(data.message);
        }
    });
});

// Registro
document.getElementById("registerForm").addEventListener("submit", function(e){
    e.preventDefault();
    if(!document.getElementById("terms").checked){
        alert("Debes aceptar los términos");
        return;
    }
    let formData = new FormData(this);

    fetch("php/registro.php", {method:"POST", body: formData})
    .then(r=>r.json())
    .then(data=>{
        if(data.success){
            alert("Cuenta creada correctamente!");
            mostrarLogin();
        }else{
            alert(data.message);
        }
    });
});