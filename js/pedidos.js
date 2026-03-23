const form = document.getElementById("formPedido");

form.addEventListener("submit", function(e){
  e.preventDefault();

  const nombre = document.getElementById("nombre").value;
  const correo = document.getElementById("correo").value;
  const telefono = document.getElementById("telefono").value;
  const servicio = document.getElementById("servicio").value;
  const mensaje = document.getElementById("mensaje").value;

  /* EXPRESIONES REGULARES */
  const regexNombre = /^[a-zA-ZÁ-ÿ\s]{3,40}$/;
  const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const regexTelefono = /^[0-9]{8,15}$/;

  if(!regexNombre.test(nombre)){
    alert("Nombre inválido");
    return;
  }

  if(!regexCorreo.test(correo)){
    alert("Correo inválido");
    return;
  }

  if(!regexTelefono.test(telefono)){
    alert("Teléfono inválido");
    return;
  }

  /* MENSAJE */
  const texto = `Hola PixelPro, quiero hacer un pedido:
  
Nombre: ${nombre}
Correo: ${correo}
Teléfono: ${telefono}
Servicio: ${servicio}
Detalles: ${mensaje}`;

  /* WHATSAPP */
  const numero = "18294309250"; // CAMBIA A TU NUMERO
  const urlWhatsapp = `https://wa.me/${numero}?text=${encodeURIComponent(texto)}`;

  /* CORREO */
  const urlCorreo = `mailto:contacto@pixelpro.com?subject=Pedido PixelPro&body=${encodeURIComponent(texto)}`;

  /* ABRIR */
  window.open(urlWhatsapp, "_blank");
  window.location.href = urlCorreo;
});