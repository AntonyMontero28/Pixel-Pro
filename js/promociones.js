let tiempo = 1800;

setInterval(() => {
  let m = Math.floor(tiempo / 60);
  let s = tiempo % 60;

  document.getElementById("timer").innerHTML = `${m}:${s}`;
  tiempo--;
}, 1000);

function enviar(promo){
  const mensaje = `Hola Pixel Pro, quiero aprovechar: ${promo}`;
  const numero = "18294309250";
  const url = `https://wa.me/${numero}?text=${encodeURIComponent(mensaje)}`;
  window.open(url, "_blank");
}