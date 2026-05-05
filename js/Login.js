// ── Toggle formularios ─────────────────
function mostrarRegistro() {
    document.getElementById('loginBox').classList.add('hidden');
    document.getElementById('registerBox').classList.remove('hidden');
}

function mostrarLogin() {
    document.getElementById('registerBox').classList.add('hidden');
    document.getElementById('loginBox').classList.remove('hidden');
}

// ── MODALES ───────────────────────────
const textos = {
    privacidad: `<h3>Política de Privacidad</h3>
    <p>Tus datos se usan solo para tu cuenta.</p>`,

    terminos: `<h3>Términos</h3>
    <p>Uso responsable de la plataforma.</p>`
};

function mostrarModal(tipo) {
    document.getElementById('contenidoModal').innerHTML = textos[tipo];
    document.getElementById('modal').classList.add('active');
}

function cerrarModal() {
    document.getElementById('modal').classList.remove('active');
}

document.getElementById('modal').addEventListener('click', function (e) {
    if (e.target === this) cerrarModal();
});