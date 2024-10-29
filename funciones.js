let cartItems = []; // Arreglo para guardar los items del carrito
const cartCountElement = document.getElementById('cart-count');
const cartButton = document.getElementById('cart-button');

// Función para agregar un artículo al carrito
function addToCart(item) {
    cartItems.push(item); // Agregar el artículo al carrito
    updateCartCount(); // Actualizar el conteo en el botón
}

// Función para actualizar el conteo del carrito
function updateCartCount() {
    const itemCount = cartItems.length; // Obtener la cantidad de items
    cartCountElement.textContent = itemCount; // Actualizar el número en el botón
    cartButton.style.display = itemCount > 0 ? 'block' : 'none'; // Mostrar u ocultar el botón
}

// Ejemplo de cómo agregar un artículo al carrito
document.querySelectorAll('.add-to-cart-button').forEach(button => {
    button.addEventListener('click', () => {
        const item = button.getAttribute('data-item'); // O el método que uses para obtener el item
        addToCart(item);
    });
});
