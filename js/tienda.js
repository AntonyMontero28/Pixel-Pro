const products = [
    {name: "Flyers", price: 150},
    {name: "Tarjetas", price: 200},
    {name: "Banners", price: 500},
    {name: "Stickers", price: 120},
    {name: "Posters", price: 250},
    {name: "Fotos", price: 180},
];

const grid = document.getElementById("productGrid");
const cartItems = document.getElementById("cartItems");
const totalEl = document.getElementById("total");
const search = document.getElementById("search");

let total = 0;

// Mostrar productos
function renderProducts(list) {
    grid.innerHTML = "";

    list.forEach(p => {
        const card = document.createElement("div");
        card.classList.add("card");

        card.innerHTML = `
            <div class="img-box">Imagen</div>
            <div class="price">RD$ ${p.price}</div>
            <div class="name">${p.name}</div>
            <div class="btns">
                <button class="buy">Ordenar</button>
                <button class="cartBtn">Agregar</button>
            </div>
        `;

        card.querySelector(".cartBtn").addEventListener("click", () => {
            addToCart(p);
        });

        grid.appendChild(card);
    });
}

// Carrito
function addToCart(product) {
    const li = document.createElement("li");
    li.textContent = `${product.name} - RD$ ${product.price}`;
    cartItems.appendChild(li);

    total += product.price;
    totalEl.textContent = total;
}

// Buscador
search.addEventListener("input", () => {
    const value = search.value.toLowerCase();
    const filtered = products.filter(p =>
        p.name.toLowerCase().includes(value)
    );
    renderProducts(filtered);
});

// Inicial
renderProducts(products);