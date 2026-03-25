let productos = document.querySelectorAll(".hola");

productos.forEach(function(cajon) {
  cajon.addEventListener("mouseover", function() {
    cajon.style.transform = "scale(1.1)";
    cajon.style.transition = "0.5s"
    cajon.classList.add('brillo');
  });

  cajon.addEventListener("mouseout", function() {
    cajon.style.transform = "scale(1)";
    cajon.classList.remove('brillo');

  });
});

