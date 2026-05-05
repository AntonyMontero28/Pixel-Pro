console.log("Proyecto cargado correctamente");

// DUPLICAR RESEÑAS PARA LOOP INFINITO
const track = document.querySelector(".reviews-track");

track.innerHTML += track.innerHTML;

const menu = document.getElementById("mobile-menu");
const navLinks = document.querySelector(".nav-links");

menu.addEventListener("click", () => {
    navLinks.classList.toggle("active");
    menu.classList.toggle("active"); // opcional para animar las barras
});