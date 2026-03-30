// Cambiar entre login y registro
function mostrarRegistro(){
  document.getElementById("loginBox").classList.add("hidden");
  document.getElementById("registerBox").classList.remove("hidden");
}

function mostrarLogin(){
  document.getElementById("registerBox").classList.add("hidden");
  document.getElementById("loginBox").classList.remove("hidden");
}

// MODAL
function mostrarModal(tipo){
  const modal = document.getElementById("modal");
  const contenido = document.getElementById("contenidoModal");

  if(tipo === "privacidad"){
    contenido.innerHTML = `
    <h3>Política de Privacidad</h3>
    <p>En PixelPro protegemos tus datos personales. No compartimos tu información con terceros y solo se utiliza para gestionar tus pedidos.</p>
    `;
  }

  if(tipo === "terminos"){
    contenido.innerHTML = `
    <h3>Términos y Condiciones</h3>
    <p>Al usar PixelPro aceptas que los pedidos realizados deben ser pagados antes de la entrega. La empresa no se responsabiliza por errores enviados por el cliente.</p>
    `;
  }

  modal.style.display = "flex";
}

function cerrarModal(){
  document.getElementById("modal").style.display = "none";
}

// VALIDACIÓN BÁSICA
document.getElementById("registerForm").addEventListener("submit", function(e){
  if(!document.getElementById("terms").checked){
    alert("Debes aceptar los términos");
    e.preventDefault();
  }else{
    alert("Cuenta creada ");
  }
});