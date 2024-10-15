<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', "1024M");
ini_set('max_execution_time', 600);

require '../src/db.php'; 
require '../src/controller.php'; 

// Crée les instances de la BDD et établit la connexion avec une instance de ProductController
$db = (new Database())->connect(); 
$productController = new ProductController($db); 

// Insertion dans la base de données
if (isset($_POST['insert-product'])) {
    $productController->createProduct(); 
}

// Suppression d'une entrée dans la base de données
if (isset($_POST['delete-product'])) {
    $productController->deleteProduct($_POST['id-product']); 
}

// Mise à jour d'une entrée dans la base de données
if (isset($_POST['update-product'])) {
    $productController->updateProduct($_POST['id-product']); 
}

// Récupération des produits
$query = $db->prepare("SELECT product_aid, product_maker, product_brand, product_name_fr, product_priceUSD, product_stock_units FROM wp_k_products");
$query->execute();
$products = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <style>
        #form_product {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h1>Gestion des Produits</h1>
<?php if ($products): ?>
    <table>
        <tr>
            <th>Fabricant</th>
            <th>Marque</th>
            <th>Nom</th>
            <th>Prix (USD)</th>
            <th>Unité de Stock</th>
            <th>Tags</th>
            <th>Ligne</th>
            <th>Catégorie</th>
            <th>Sous-catégorie</th>
            <th>Action</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <form class="form-products" method="POST">
                <tr>
                    <td><input type="text" name="maker" value="<?= htmlspecialchars($product->product_maker ?? 'N/A') ?>" required/></td>
                    <td><input type="text" name="brand" value="<?= htmlspecialchars($product->product_brand ?? 'N/A') ?>" required/></td>
                    <td><input type="text" name="name" value="<?= htmlspecialchars($product->product_name_fr ?? 'N/A') ?>" required/></td>
                    <td><input type="number" name="priceUSD" value="<?= htmlspecialchars($product->product_priceUSD ?? '0.00') ?>" step="0.01" required/></td>
                    <td><input type="number" name="stockUnit" value="<?= htmlspecialchars($product->product_stock_units ?? '0') ?>" min="0" required/></td>
                    <td><input type="text" name="tags" value="<?= htmlspecialchars($product->product_tags_fr ?? 'N/A') ?>"/></td>
                    <td><input type="text" name="line" value="<?= htmlspecialchars($product->product_line_fr ?? 'N/A') ?>"/></td>
                    <td><input type="text" name="category" value="<?= htmlspecialchars($product->product_category_fr ?? 'N/A') ?>"/></td>
                    <td><input type="text" name="subCategory" value="<?= htmlspecialchars($product->product_subcategory_fr ?? 'N/A') ?>"/></td>
                    <td>
                        <input type="hidden" name="id-product" value="<?= htmlspecialchars($product->product_aid) ?>"/>
                        <button class="delete-product" name="delete-product">Supprimer</button>
                        <button class="update-product" name="update-product">Modifier</button>
                    </td>
                </tr>
            </form>
        <?php endforeach;?>
    </table>
<?php else: ?>
    <p>Aucun produit trouvé.</p>
<?php endif; ?>

<form id="form_product" method="POST">
    <h2>Ajouter un Nouveau Produit</h2>
    <label for="maker">Fabricant :</label>
    <input type="text" id="maker" name="maker" required>

    <label for="brand">Marque :</label>
    <input type="text" id="brand" name="brand" required>

    <label for="name">Nom :</label>
    <input type="text" id="name" name="name" required>

    <label for="priceUSD">Prix (USD) :</label>
    <input type="number" id="priceUSD" name="priceUSD" required step="0.01" min="0">

    <label for="stockUnit">Unité de stock :</label>
    <input type="number" id="stockUnit" name="stockUnit" required min="0">

    <label for="tags">Tags :</label>
    <input type="text" id="tags" name="tags">

    <label for="line">Ligne :</label>
    <input type="text" id="line" name="line">

    <label for="category">Catégorie :</label>
    <input type="text" id="category" name="category">

    <label for="subCategory">Sous-catégorie :</label>
    <input type="text" id="subCategory" name="subCategory">

    <label for="image">Image URL :</label>
    <input type="text" id="image" name="image">

    <label for="stockStatus">État du stock :</label>
    <input type="text" id="stockStatus" name="stockStatus" required>

    <label for="priceEUR">Prix (EUR) :</label>
    <input type="number" id="priceEUR" name="priceEUR" required step="0.01" min="0">

    <label for="description">Description :</label>
    <textarea id="description" name="description"></textarea>

    <input type="submit" name="insert-product" id="submit-btn" value="Ajouter le Produit">
</form>

<script src="public/js/script.js"></script> 
</body>
</html>
