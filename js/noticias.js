// js/Login.js

// ── Toggle entre Login y Registro ──────────────────────────
function mostrarRegistro() {
  document.getElementById('loginBox').classList.add('hidden');
  document.getElementById('registerBox').classList.remove('hidden');
}

function mostrarLogin() {
  document.getElementById('registerBox').classList.add('hidden');
  document.getElementById('loginBox').classList.remove('hidden');
}

// ── Modales de Términos / Privacidad ───────────────────────
const textos = {
  privacidad: `<h3>Política de Privacidad</h3>
    <p>Tus datos personales (nombre, teléfono y correo) son utilizados únicamente
    para gestionar tu cuenta en PixelPro. No compartimos tu información con terceros.</p>`,
  terminos: `<h3>Términos y Condiciones</h3>
    <p>Al registrarte aceptas usar la plataforma de forma responsable.
    PixelPro se reserva el derecho de suspender cuentas que infrinjan las normas de uso.</p>`
};

function mostrarModal(tipo) {
  document.getElementById('contenidoModal').innerHTML = textos[tipo];
  const modal = document.getElementById('modal');
  modal.style.display = 'flex';
}

function cerrarModal() {
  document.getElementById('modal').style.display = 'none';
}

// ── Helpers ────────────────────────────────────────────────
function mostrarMensaje(formId, texto, esError) {
  // Eliminar mensaje anterior si existe
  const anterior = document.querySelector('.msg-respuesta');
  if (anterior) anterior.remove();

  const p = document.createElement('p');
  p.className = 'msg-respuesta';
  p.textContent = texto;
  p.style.cssText = `
    margin-top: 10px;
    font-size: 13px;
    color: ${esError ? '#ff6b6b' : '#00e676'};
    font-weight: bold;
  `;
  document.getElementById(formId).appendChild(p);
}

// ── Formulario REGISTRO ────────────────────────────────────
document.getElementById('registerForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const inputs  = this.querySelectorAll('input[type="text"], input[type="tel"], input[type="email"], input[type="password"]');
  const nombre   = inputs[0].value.trim();
  const telefono = inputs[1].value.trim();
  const correo   = inputs[2].value.trim();
  const password = inputs[3].value;
  const terms    = document.getElementById('terms').checked;

  if (!terms) {
    mostrarMensaje('registerForm', 'Debes aceptar los términos y la política de privacidad.', true);
    return;
  }

  const btn = this.querySelector('button');
  btn.disabled = true;
  btn.textContent = 'Registrando...';

  const data = new FormData();
  data.append('nombre',   nombre);
  data.append('telefono', telefono);
  data.append('correo',   correo);
  data.append('password', password);

  try {
    const res  = await fetch('php/registro.php', { method: 'POST', body: data });
    const json = await res.json();

    mostrarMensaje('registerForm', json.msg, !json.ok);

    if (json.ok) {
      setTimeout(() => {
        this.reset();
        mostrarLogin();
      }, 1500);
    }
  } catch (err) {
    mostrarMensaje('registerForm', 'Error de conexión. Verifica el servidor.', true);
  } finally {
    btn.disabled = false;
    btn.textContent = 'Registrarse';
  }
});

// ── Formulario LOGIN ───────────────────────────────────────
document.getElementById('loginForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const inputs   = this.querySelectorAll('input');
  const correo   = inputs[0].value.trim();
  const password = inputs[1].value;

  const btn = this.querySelector('button');
  btn.disabled = true;
  btn.textContent = 'Entrando...';

  const data = new FormData();
  data.append('correo',   correo);
  data.append('password', password);

  try {
    const res  = await fetch('php/login.php', { method: 'POST', body: data });
    const json = await res.json();

    mostrarMensaje('loginForm', json.msg, !json.ok);

    if (json.ok) {
      // Redirige al inicio después de 1 segundo
      setTimeout(() => {
        window.location.href = 'index.html';
      }, 1000);
    }
  } catch (err) {
    mostrarMensaje('loginForm', 'Error de conexión. Verifica el servidor.', true);
  } finally {
    btn.disabled = false;
    btn.textContent = 'Entrar';
  }
});