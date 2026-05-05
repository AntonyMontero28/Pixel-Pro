// Buscador
const searchInput = document.getElementById("searchInput");
const cards = document.querySelectorAll(".card");
searchInput.addEventListener("input", () => {
  const query = searchInput.value.toLowerCase();
  cards.forEach((card) => {
    const name = card.querySelector("h3").textContent.toLowerCase();
    card.style.display = name.includes(query) ? "flex" : "none";
  });
});

// Menú hamburguesa
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
