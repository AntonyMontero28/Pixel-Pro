function scrollServicios(){
    document.getElementById("noticias").scrollIntoView({
        behavior: "smooth"
    });
}

function mensaje(){
    alert("Gracias por contactarnos 😊 Pronto te responderemos.");
}

/* Animación al hacer scroll */
window.addEventListener("scroll", function(){
    let cards = document.querySelectorAll(".card");

    cards.forEach(card => {
        let position = card.getBoundingClientRect().top;
        let screen = window.innerHeight;

        if(position < screen - 100){
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }
    });
});