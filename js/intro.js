// LOADER TRANSICIÓN
window.addEventListener("load", () => {
  const loader = document.getElementById("loader");
  setTimeout(() => {
    loader.style.opacity = "0";
    loader.style.pointerEvents = "none";
  }, 1800);
});

// TRANSICIÓN AL INDEX
const btn = document.querySelector(".btn");
btn.addEventListener("click", function(e){
  e.preventDefault();
  document.body.style.transition = "1s";
  document.body.style.opacity = "0";

  setTimeout(()=>{
    window.location.href = "index.html";
  },1000);
});

// CURSOR PERSONALIZADO
const cursor = document.createElement("div");
cursor.classList.add("cursor");
document.body.appendChild(cursor);

document.addEventListener("mousemove", (e)=>{
  cursor.style.left = e.clientX + "px";
  cursor.style.top = e.clientY + "px";
});

// EFECTO TINTA (PARTÍCULAS)
document.addEventListener("mousemove", function(e){
  let particle = document.createElement("div");
  particle.classList.add("particle");

  particle.style.left = e.clientX + "px";
  particle.style.top = e.clientY + "px";

  document.body.appendChild(particle);

  setTimeout(()=>{
    particle.remove();
  },1000);
});

// ANIMACIÓN AL HACER SCROLL
const elements = document.querySelectorAll(".fade-up");

window.addEventListener("scroll", ()=>{
  elements.forEach(el=>{
    const position = el.getBoundingClientRect().top;
    const screen = window.innerHeight;

    if(position < screen - 100){
      el.classList.add("active");
    }
  });
});

// REVEAL PRO
const reveals = document.querySelectorAll(".reveal");

window.addEventListener("scroll", ()=>{
  reveals.forEach(el=>{
    const top = el.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;

    if(top < windowHeight - 100){
      el.classList.add("active");
    }
  });
});

// CURSOR INTERACTIVO
const links = document.querySelectorAll("a, button");

links.forEach(link=>{
  link.addEventListener("mouseover", ()=>{
    document.querySelector(".cursor").classList.add("active");
  });

  link.addEventListener("mouseout", ()=>{
    document.querySelector(".cursor").classList.remove("active");
  });
});