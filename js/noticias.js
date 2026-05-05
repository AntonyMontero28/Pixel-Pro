let cards = document.querySelectorAll(".card");

cards.forEach((card) => {
  card.addEventListener("click", function () {
    // Quita el efecto a todos
    cards.forEach((c) => c.classList.remove("activo"));

    // Activa solo el que tocaste
    card.classList.add("activo");
  });
});

const menu = document.getElementById("mobile-menu");
const nav = document.querySelector(".nav-links");
const links = document.querySelectorAll(".nav-links li");

menu.addEventListener("click", () => {
  // activar menú
  nav.classList.toggle("nav-active");

  // animación de links
  links.forEach((link, index) => {
    if (link.style.animation) {
      link.style.animation = "";
    } else {
      link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`;
    }
  });

  // animación hamburguesa
  menu.classList.toggle("toggle");
});


