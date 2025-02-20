const products = [
    { name: "Produit 1", price: "29,99 €", available: "Disponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 2", price: "49,99 €", available: "Disponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 3", price: "19,99 €", available: "Indisponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 4", price: "99,99 €", available: "Disponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 5", price: "15,99 €", available: "Indisponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 6", price: "39,99 €", available: "Disponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 7", price: "25,00 €", available: "Disponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 8", price: "79,99 €", available: "Indisponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 9", price: "59,99 €", available: "Disponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 10", price: "89,99 €", available: "Disponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 11", price: "39,99 €", available: "Indisponible", image: "{{ asset('asset/default-image.jpg') }}" },
    { name: "Produit 12", price: "69,99 €", available: "Disponible", image: "{{ asset('asset/default-image.jpg') }}" }
];
// On changera ici, par un json envoyer depuis la base de donnée


const productsPerPage = 4; // Nombre de produit a aficher par page
let currentPage = 1;

// Fonction pour aficher les produit
function displayProducts(page) {
    const startIndex = (page - 1) * productsPerPage;
    const endIndex = page * productsPerPage;
    const productsToDisplay = products.slice(startIndex, endIndex);

    const productContainer = document.getElementById('product-container');
    productContainer.innerHTML = "";

    productsToDisplay.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('product-card');
        productCard.innerHTML = `
            <img src="${product.image}" alt="${product.name}" loading="lazy" class="product-image" onerror="this.src='{{ asset('asset/default-image.jpg') }}'" />
            <h3>${product.name}</h3>
            <p>${product.price}</p>
            <p>${product.available}</p>
        `;
        productContainer.appendChild(productCard);
    });

    // Gestion des bouton de pagination
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');

    prevBtn.disabled = page === 1;
    nextBtn.disabled = page * productsPerPage >= products.length;
}

// Fonction de pagination
function handlePagination(action) {
    if (action === 'next' && currentPage * productsPerPage < products.length) {
        currentPage++;
    } else if (action === 'prev' && currentPage > 1) {
        currentPage--;
    }
    displayProducts(currentPage);
}

// Ajout des evenements pour les bouton
document.getElementById('next-btn').addEventListener('click', () => handlePagination('next'));
document.getElementById('prev-btn').addEventListener('click', () => handlePagination('prev'));

// Initialisation
displayProducts(currentPage);