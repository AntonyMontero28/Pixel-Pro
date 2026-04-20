let totalGlobal = 0;

function calcular(){

  const cliente = document.getElementById("cliente").value;
  const tipo = document.getElementById("tipo").value;
  const cantidad = parseInt(document.getElementById("cantidad").value);
  const material = document.getElementById("material").value;

  let precioBase = 0;

  if(tipo === "Color") precioBase = 10;
  if(tipo === "B/N") precioBase = 5;
  if(tipo === "Fotográfica") precioBase = 50;

  let extra = 0;
  if(material === "Satinado") extra = 5;
  if(material === "Vinil") extra = 10;

  const total = (precioBase + extra) * cantidad;
  totalGlobal = total;

  document.getElementById("total").innerText = "RD$ " + total;

  /* FACTURA */
  document.getElementById("factura").style.display = "block";
  document.getElementById("f-cliente").innerText = "Cliente: " + cliente;
  document.getElementById("f-servicio").innerText = "Servicio: " + tipo;
  document.getElementById("f-cantidad").innerText = "Cantidad: " + cantidad;
  document.getElementById("f-material").innerText = "Material: " + material;
  document.getElementById("f-total").innerText = "TOTAL: RD$ " + total;
}

/* ENVIAR FACTURA */
function enviarFactura(){

  const cliente = document.getElementById("cliente").value;
  const tipo = document.getElementById("tipo").value;
  const cantidad = document.getElementById("cantidad").value;
  const material = document.getElementById("material").value;

  const mensaje = `🧾 *FACTURA PIXEL PRO*

Cliente: ${cliente}
Servicio: ${tipo}
Cantidad: ${cantidad}
Material: ${material}

💰 TOTAL: RD$ ${totalGlobal}

Gracias por confiar en Pixel Pro 🚀`;

  const numero = "18294309250";

  const url = `https://wa.me/${numero}?text=${encodeURIComponent(mensaje)}`;

  window.open(url, "_blank");

  guardarCaja(cliente, totalGlobal);
}

/* GUARDAR EN CAJA */
function guardarCaja(cliente, total){

  let caja = JSON.parse(localStorage.getItem("caja")) || [];

  caja.push({
    cliente: cliente,
    total: total,
    fecha: new Date().toLocaleString()
  });

  localStorage.setItem("caja", JSON.stringify(caja));
}