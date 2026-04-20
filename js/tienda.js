// CURSOR PERSONALIZADO
const cursor = document.createElement("div");
cursor.classList.add("cursor");
document.body.appendChild(cursor);

document.addEventListener("mousemove", (e)=>{
  cursor.style.left = e.clientX + "px";
  cursor.style.top = e.clientY + "px";
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

