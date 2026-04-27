// ========== DATOS DE PRODUCTOS ==========
const productos = [
    {
        id: 1,
        nombre: 'All Take Mood Like Palme',
        categoria: 'Paris',
        precio: 20.50,
        emoji: '🎨',
        badge: 'BEST SELLER',
        badgeClass: 'best-seller'
    },
    {
        id: 2,
        nombre: 'Gifting Mixing Balm',
        categoria: 'Skincare',
        precio: 17.90,
        emoji: '✨',
        badge: 'NUEVO',
        badgeClass: ''
    },
    {
        id: 3,
        nombre: 'Soft Peach Liquid Blush',
        categoria: 'Hair Care',
        precio: 23.00,
        emoji: '🌸',
        badge: 'POPULAR',
        badgeClass: ''
    },
    {
        id: 4,
        nombre: 'Sunscreen Fluid Base',
        categoria: 'Beauty',
        precio: 40.00,
        emoji: '☀️',
        badge: '',
        badgeClass: ''
    },
    {
        id: 5,
        nombre: 'Facial Treatment Pack',
        categoria: 'Facial Treatment',
        precio: 84.00,
        emoji: '💆',
        badge: 'BEST SELLER',
        badgeClass: 'best-seller'
    },
    {
        id: 6,
        nombre: 'Moisturizing Cream',
        categoria: 'Skincare',
        precio: 24.00,
        emoji: '🧴',
        badge: '',
        badgeClass: ''
    },
    {
        id: 7,
        nombre: 'Concealer Palette',
        categoria: 'Makeup',
        precio: 34.00,
        emoji: '🎭',
        badge: 'OFERTA',
        badgeClass: ''
    },
    {
        id: 8,
        nombre: 'Sugar Twinkle Duo Eye Stick',
        categoria: 'Makeup',
        precio: 28.50,
        emoji: '💫',
        badge: 'NUEVO',
        badgeClass: ''
    }
];

// ========== CARRITO ==========
let carrito = [];

// ========== CARGAR PRODUCTOS ==========
function cargarProductos() {
    const grid = document.getElementById('productsGrid');
    
    productos.forEach(producto => {
        const card = document.createElement('div');
        card.className = 'product-card';
        
        card.innerHTML = `
            ${producto.badge ? `<span class="product-badge ${producto.badgeClass}">${producto.badge}</span>` : ''}
            <div class="product-image">${producto.emoji}</div>
            <div class="product-info">
                <span class="product-category">${producto.categoria}</span>
                <h3 class="product-name">${producto.nombre}</h3>
                <p class="product-price">$${producto.precio.toFixed(2)}</p>
                <button class="btn-add-cart" onclick="agregarAlCarrito(${producto.id})">
                    🛒 Añadir al Carrito
                </button>
            </div>
        `;
        
        grid.appendChild(card);
    });
}

// ========== AGREGAR AL CARRITO ==========
function agregarAlCarrito(idProducto) {
    const producto = productos.find(p => p.id === idProducto);
    
    const itemEnCarrito = carrito.find(item => item.id === idProducto);
    
    if (itemEnCarrito) {
        itemEnCarrito.cantidad++;
    } else {
        carrito.push({
            ...producto,
            cantidad: 1
        });
    }
    
    actualizarCarrito();
    mostrarToast(`${producto.emoji} ${producto.nombre} añadido al carrito`);
    
    // Animación del botón
    const botones = document.querySelectorAll('.btn-add-cart');
    botones.forEach(btn => {
        if (btn.onclick.toString().includes(`(${idProducto})`)) {
            btn.classList.add('added');
            btn.textContent = '✅ Añadido';
            setTimeout(() => {
                btn.classList.remove('added');
                btn.textContent = '🛒 Añadir al Carrito';
            }, 1200);
        }
    });
}

// ========== ACTUALIZAR CARRITO ==========
function actualizarCarrito() {
    const cartItems = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    const cartTotal = document.getElementById('cartTotal');
    const btnCheckout = document.getElementById('btnCheckout');
    
    // Actualizar contador
    const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
    cartCount.textContent = totalItems;
    
    // Si el carrito está vacío
    if (carrito.length === 0) {
        cartItems.innerHTML = `
            <div class="cart-empty">
                <p>🛍️</p>
                <p>Tu carrito está vacío</p>
            </div>
        `;
        cartTotal.style.display = 'none';
        btnCheckout.style.display = 'none';
        return;
    }
    
    // Mostrar items del carrito
    cartTotal.style.display = 'flex';
    btnCheckout.style.display = 'block';
    
    cartItems.innerHTML = carrito.map(item => `
        <div class="cart-item">
            <div class="cart-item-image">${item.emoji}</div>
            <div class="cart-item-info">
                <p class="cart-item-name">${item.nombre}</p>
                <p class="cart-item-price">$${item.precio.toFixed(2)}</p>
                <div class="cart-item-quantity">
                    <button class="qty-btn" onclick="cambiarCantidad(${item.id}, -1)">−</button>
                    <span>${item.cantidad}</span>
                    <button class="qty-btn" onclick="cambiarCantidad(${item.id}, 1)">+</button>
                </div>
            </div>
            <button class="remove-item" onclick="eliminarDelCarrito(${item.id})">🗑️</button>
        </div>
    `).join('');
    
    // Actualizar total
    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    cartTotal.innerHTML = `
        <span>Total:</span>
        <span>$${total.toFixed(2)}</span>
    `;
}

// ========== CAMBIAR CANTIDAD ==========
function cambiarCantidad(idProducto, cambio) {
    const item = carrito.find(item => item.id === idProducto);
    
    if (item) {
        item.cantidad += cambio;
        
        if (item.cantidad <= 0) {
            eliminarDelCarrito(idProducto);
            return;
        }
        
        actualizarCarrito();
    }
}

// ========== ELIMINAR DEL CARRITO ==========
function eliminarDelCarrito(idProducto) {
    const producto = carrito.find(p => p.id === idProducto);
    carrito = carrito.filter(item => item.id !== idProducto);
    actualizarCarrito();
    
    if (producto) {
        mostrarToast(`❌ ${producto.nombre} eliminado del carrito`);
    }
}

// ========== TOGGLE CARRITO ==========
function toggleCart() {
    const overlay = document.getElementById('cartOverlay');
    overlay.classList.toggle('open');
}

// ========== CERRAR CARRITO AL HACER CLIC FUERA ==========
document.addEventListener('click', function(e) {
    const overlay = document.getElementById('cartOverlay');
    if (e.target === overlay) {
        overlay.classList.remove('open');
    }
});

// ========== CHECKOUT ==========
function checkout() {
    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    if (total > 0) {
        mostrarToast(`🎉 ¡Gracias por tu compra! Total: $${total.toFixed(2)}`);
        carrito = [];
        actualizarCarrito();
        document.getElementById('cartOverlay').classList.remove('open');
    }
}

// ========== TOAST DE NOTIFICACIÓN ==========
function mostrarToast(mensaje) {
    const toast = document.getElementById('toast');
    toast.textContent = mensaje;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2500);
}

// ========== INICIALIZAR ==========
document.addEventListener('DOMContentLoaded', () => {
    cargarProductos();
    actualizarCarrito();
});