 let cards = document.querySelectorAll(".card");

cards.forEach(card => {
    card.addEventListener("click", function(){

        // Quita el efecto a todos
        cards.forEach(c => c.classList.remove("activo"));

        // Activa solo el que tocaste
        card.classList.add("activo");
    });
});