const datos = document.getElementById("datos");
const totalGeneral = document.getElementById("totalGeneral");

let caja = JSON.parse(localStorage.getItem("caja")) || [];

let total = 0;

caja.forEach(p => {

  const fila = document.createElement("div");
  fila.classList.add("fila","fila-dato");

  fila.innerHTML = `
    <div>${p.cliente}</div>
    <div>RD$ ${p.total}</div>
    <div>${p.fecha}</div>
  `;

  datos.appendChild(fila);

  total += parseFloat(p.total);
});

totalGeneral.innerText = "Total General: RD$ " + total;