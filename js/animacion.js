const cajones = document.querySelectorAll('.caj4');

cajones.forEach(cajon => {
    cajon.addEventListener('mouseenter', () => {
        cajon.classList.add('caj4-hover');
    });

    cajon.addEventListener('mouseleave', () => {
        cajon.classList.remove('caj4-hover');
    });
});


const miniCajones = document.querySelectorAll('.caj2-5');

miniCajones.forEach(cajon => {
    cajon.addEventListener('mouseenter', () => {
        cajon.classList.add('caj2-5-active');
    });

    cajon.addEventListener('mouseleave', () => {
        cajon.classList.remove('caj2-5-active');
    });
});

document.getElementById('menu-toggle').addEventListener('click', function() {
    const navs = document.querySelectorAll('nav');
    navs.forEach(nav => nav.classList.toggle('active'));
});