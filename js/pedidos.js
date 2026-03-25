const form = document.getElementById("formPedido");

form.addEventListener("submit", function(e){
  e.preventDefault();

  const nombre = document.getElementById("nombre").value;
  const correo = document.getElementById("correo").value;
  const telefono = document.getElementById("telefono").value;
  const servicio = document.getElementById("servicio").value;
  const mensaje = document.getElementById("mensaje").value;

  const regexNombre = /^[a-zA-ZÁ-ÿ\s]{3,40}$/;
  const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const regexTelefono = /^[0-9]{8,15}$/;

  if(!regexNombre.test(nombre)){ alert("Nombre inválido"); return; }
  if(!regexCorreo.test(correo)){ alert("Correo inválido"); return; }
  if(!regexTelefono.test(telefono)){ alert("Teléfono inválido"); return; }

  const texto = `Hola PixelPro, quiero hacer un pedido:
Nombre: ${nombre}
Correo: ${correo}
Teléfono: ${telefono}
Servicio: ${servicio}
Detalles: ${mensaje}`;

  // WhatsApp
  const numero = "18294309250"; 
  window.open(`https://wa.me/${numero}?text=${encodeURIComponent(texto)}`, "_blank");

  // Correo
  window.location.href = `mailto:pixelproinpresionesdigitales@gmail.com?subject=Pedido PixelPro&body=${encodeURIComponent(texto)}`;

  // Guardar en base de datos
  fetch("php/guardarPedido.php", {
    method:"POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({nombre, correo, telefono, servicio, mensaje})
  })
  .then(res => res.text())
  .then(res => {
    alert("Pedido registrado en la pizarra PixelPro ✅");
    form.reset();
  })
  .catch(err => console.error(err));
});