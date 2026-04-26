// LOADER
window.addEventListener("load", () => {
    const loader = document.getElementById("loader");

    setTimeout(() => {
        loader.style.opacity = "0";
        loader.style.visibility = "hidden";
    }, 3500);
});

// TRANSICIÓN BOTONES
const botones = document.querySelectorAll(".btn");

botones.forEach(boton => {
    boton.addEventListener("click", function(e) {
        e.preventDefault();

        document.body.style.opacity = "0";
        document.body.style.transition = "1s";

        const destino = this.getAttribute("href");

        setTimeout(() => {
            window.location.href = destino;
        }, 1000);
    });
});

// EFECTO PARALLAX TEXTO
document.addEventListener("mousemove", (e) => {
    const x = (window.innerWidth / 2 - e.clientX) / 50;
    const y = (window.innerHeight / 2 - e.clientY) / 50;

    document.querySelector(".contenido").style.transform =
        `translate(${x}px, ${y}px)`;
});