const searchInput = document.getElementById('searchInput');
    const cards = document.querySelectorAll('.card');
 
    searchInput.addEventListener('input', () => {
      const query = searchInput.value.toLowerCase();
      cards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        card.style.display = name.includes(query) ? 'flex' : 'none';
      });
    });