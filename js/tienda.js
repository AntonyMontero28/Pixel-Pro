// Buscador
    const searchInput = document.getElementById('searchInput');
    const cards = document.querySelectorAll('.card');
    searchInput.addEventListener('input', () => {
      const query = searchInput.value.toLowerCase();
      cards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        card.style.display = name.includes(query) ? 'flex' : 'none';
      });
    });

    // Menú hamburguesa
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      mobileMenu.classList.toggle('open');
    });
    // Cerrar menú al tocar un link
    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        hamburger.classList.remove('active');
        mobileMenu.classList.remove('open');
      });
    });