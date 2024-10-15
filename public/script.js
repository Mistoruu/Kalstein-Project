// Sélectionner les éléments du DOM
const formProduct = document.getElementById('form_product');
const maker = document.getElementById('maker');
const brand = document.getElementById('brand');
const name = document.getElementById('name');
const priceUSD = document.getElementById('priceUSD');
const stockUnit = document.getElementById('stockUnit');
const tags = document.getElementById('tags');
const line = document.getElementById('line');
const category = document.getElementById('category');
const subCategory = document.getElementById('subCategory');
const image = document.getElementById('image');
const stockStatus = document.getElementById('stockStatus');
const priceEUR = document.getElementById('priceEUR');
const description = document.getElementById('description');
const submitBtn = document.getElementById('submit-btn');

// Fonction de validation
function validateForm() {
    let errors = [];

    if (!maker.value.trim()) {
        errors.push("Le champ Fabricant est obligatoire.");
    }
    if (!brand.value.trim()) {
        errors.push("Le champ Marque est obligatoire.");
    }
    if (!name.value.trim()) {
        errors.push("Le champ Nom est obligatoire.");
    }
    if (isNaN(priceUSD.value) || parseFloat(priceUSD.value) <= 0) {
        errors.push("Le prix (USD) doit être un nombre positif.");
    }
    if (isNaN(stockUnit.value) || parseInt(stockUnit.value) < 0) {
        errors.push("L'unité de stock doit être un entier non négatif.");
    }
    if (!stockStatus.value.trim()) {
        errors.push("Le champ État du stock est obligatoire.");
    }
    if (isNaN(priceEUR.value) || parseFloat(priceEUR.value) < 0) {
        errors.push("Le prix (EUR) doit être un nombre positif ou égal à zéro.");
    }

    // Affichage des erreurs
    if (errors.length > 0) {
        alert(errors.join('\n'));
        return false;
    }

    return true;
}

// Événement de soumission du formulaire
formProduct.addEventListener('submit', async (event) => {
    event.preventDefault();

    if (!validateForm()) {
        return;
    }

    submitBtn.disabled = true; /

    const productData = {
        maker: maker.value,
        brand: brand.value,
        name: name.value,
        priceUSD: parseFloat(priceUSD.value),
        stockUnit: parseInt(stockUnit.value),
        tags: tags.value,
        line: line.value,
        category: category.value,
        subCategory: subCategory.value,
        image: image.value,
        stockStatus: stockStatus.value,
        priceEUR: parseFloat(priceEUR.value),
        description: description.value,
    };

    try {
        const response = await fetch('http://localhost/kalsteinProject/src/controller.php/api/produits', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(productData),
        });

        const result = await response.json();
        alert(result.message);
        if (result.message === 'Produit ajouté avec succès') {
            formProduct.reset();
            fetchProducts();
        }
    } catch (error) {
        console.error('Erreur lors de l\'ajout du produit:', error);
        alert('Erreur lors de l\'ajout du produit. Veuillez réessayer.');
    } finally {
        submitBtn.disabled = false; 
    }
});

// Fonction pour récupérer tous les produits
async function fetchProducts() {
    try {
        const response = await fetch('http://localhost/kalsteinProject/src/controller.php/api/produits');
        const products = await response.json();
        displayProducts(products);
    } catch (error) {
        console.error('Erreur lors de la récupération des produits:', error);
    }
}

// Fonction pour afficher les produits dans le tableau
function displayProducts(products) {
    const productTableBody = document.querySelector('table tbody');
    productTableBody.innerHTML = '';

    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.product_maker}</td>
            <td>${product.product_brand}</td>
            <td>${product.product_name_fr}</td>
            <td>${product.product_priceUSD}</td>
            <td>${product.product_stock_units}</td>
            <td>
                <button class="update-product" data-id="${product.product_aid}">Modifier</button>
                <button class="delete-product" data-id="${product.product_aid}">Supprimer</button>
            </td>
        `;
        productTableBody.appendChild(row);
    });

    // Ajout d'événements pour les boutons Modifier et Supprimer
    const updateButtons = document.querySelectorAll('.update-product');
    const deleteButtons = document.querySelectorAll('.delete-product');

    updateButtons.forEach(button => {
        button.addEventListener('click', async (event) => {
            const productId = event.target.dataset.id;
            // Récupérer les détails du produit à modifier et remplir le formulaire
            const product = await fetch(`http://localhost/kalsteinProject/src/controller.php/api/produits/${productId}`);
            const productData = await product.json();

            // Remplir le formulaire avec les données du produit
            maker.value = productData.product_maker;
            brand.value = productData.product_brand;
            name.value = productData.product_name_fr;
            priceUSD.value = productData.product_priceUSD;
            stockUnit.value = productData.product_stock_units;
            tags.value = productData.product_tags_fr;
            line.value = productData.product_line_fr;
            category.value = productData.product_category_fr;
            subCategory.value = productData.product_subcategory_fr;
            image.value = productData.product_image;
            stockStatus.value = productData.product_stock_status;
            priceEUR.value = productData.product_priceEUR;
            description.value = productData.product_description_fr;

            // Changer le texte du bouton pour indiquer que nous modifions un produit
            submitBtn.value = 'Modifier le Produit';

            // Une fois que le produit est modifié, réinitialiser le bouton à son état d'origine
            submitBtn.onclick = async () => {
                
                productData.product_aid = productId; 
                await fetch(`http://localhost/kalsteinProject/src/controller.php/api/produits/${productId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(productData),
                });
                fetchProducts(); 
            };
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', async (event) => {
            const productId = event.target.dataset.id;
            const confirmDelete = confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');
            if (confirmDelete) {
                try {
                    const response = await fetch(`http://localhost/kalsteinProject/src/controller.php/api/produits/${productId}`, {
                        method: 'DELETE',
                    });
                    const result = await response.json();
                    alert(result.message);
                    fetchProducts(); 
                } catch (error) {
                    console.error('Erreur lors de la suppression du produit:', error);
                    alert('Erreur lors de la suppression du produit. Veuillez réessayer.');
                }
            }
        });
    });
}

// Appel initial pour charger les produits
fetchProducts();
