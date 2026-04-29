// =========================
// ANIMACION SCROLL (SECCIONES)
// =========================
const elementos = document.querySelectorAll(".section2, .section3");

const mostrarElemento = (entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add("visible");
    }
  });
};

const observer = new IntersectionObserver(mostrarElemento, {
  threshold: 0.2
});

elementos.forEach(el => observer.observe(el));


// =========================
// EFECTO MAQUINA DE ESCRIBIR
// =========================
const texto = "Bienvenidos a PixelPro";
let i = 0;
const velocidad = 80;
const titulo = document.getElementById("vpH");

function escribir() {
  if (!titulo) return;

  if (i < texto.length) {
    titulo.textContent += texto.charAt(i);
    i++;
    setTimeout(escribir, velocidad);
  }
}

window.addEventListener("load", () => {
  if (titulo) {
    titulo.textContent = "";
    escribir();
  }
});


// =========================
// EFECTO CLICK BOTONES
// =========================
const botones = document.querySelectorAll(".btn-servicio");

botones.forEach(btn => {
  btn.addEventListener("click", () => {
    btn.style.transform = "scale(0.9)";
    setTimeout(() => {
      btn.style.transform = "scale(1)";
    }, 150);
  });
});

document.addEventListener("DOMContentLoaded", () => {

    const toggle = document.querySelector(".menu-toggle");
    const navs = document.querySelectorAll(".nav");

    toggle.addEventListener("click", () => {

        navs.forEach(nav => {
            nav.classList.toggle("active");
        });

    });

});