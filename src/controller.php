<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';
require_once 'Product.php';

class ProductController
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllProducts()
    {
        $query = $this->db->prepare("SELECT * FROM wp_k_products");
        $query->execute();

        $products = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products ?: ['message' => 'Aucun produit trouvé']);
    }

    public function getProductById($id)
    {
        $query = $this->db->prepare("SELECT * FROM wp_k_products WHERE product_aid = :aid");
        $query->bindValue(":aid", $id, PDO::PARAM_INT);
        $query->execute();

        $product = $query->fetch(PDO::FETCH_ASSOC);
        echo json_encode($product ?: ['message' => 'Produit non trouvé avec l\'ID : ' . $id]);
    }

    public function createProduct()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validation
        if (empty($data['product_name_fr'])) {
            echo json_encode(['message' => 'Le champ « nom du produit » est obligatoire.']);
            return;
        }

        if (!is_numeric($data['product_priceUSD']) || $data['product_priceUSD'] <= 0) {
            echo json_encode(['message' => 'Le prix doit être un nombre positif.']);
            return;
        }

        if (!isset($data['product_stock_units']) || !is_numeric($data['product_stock_units']) || $data['product_stock_units'] < 0) {
            echo json_encode(['message' => 'Le stock doit être un entier non négatif.']);
            return;
        }

        // Insertion
        $query = $this->db->prepare("INSERT INTO wp_k_products (product_maker, product_brand, product_tags_fr, product_line_fr, product_category_fr, product_subcategory_fr, product_name_fr, product_image, product_stock_units, product_stock_status, product_priceUSD, product_priceEUR, product_create_at, product_description_fr) VALUES (:maker, :brand, :tags, :line, :category, :subCategory, :name, :image, :stockUnit, :stockStatus, :priceUSD, :priceEUR, NOW(), :description)");

        $query->bindValue(':maker', $data['product_maker']);
        $query->bindValue(':brand', $data['product_brand']);
        $query->bindValue(':tags', $data['product_tags_fr']);
        $query->bindValue(':line', $data['product_line_fr']);
        $query->bindValue(':category', $data['product_category_fr']);
        $query->bindValue(':subCategory', $data['product_subcategory_fr']);
        $query->bindValue(':name', $data['product_name_fr']);
        $query->bindValue(':image', $data['product_image']);
        $query->bindValue(':stockUnit', $data['product_stock_units'], PDO::PARAM_INT);
        $query->bindValue(':stockStatus', $data['product_stock_status']);
        $query->bindValue(':priceUSD', $data['product_priceUSD']);
        $query->bindValue(':priceEUR', $data['product_priceEUR']);
        $query->bindValue(':description', $data['product_description_fr']);

        echo json_encode($query->execute() ? ['message' => 'Produit ajouté avec succès'] : ['message' => 'Erreur lors de l\'ajout du produit']);
    }

    public function updateProduct($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validation
        if (empty($data['product_name_fr'])) {
            echo json_encode(['message' => 'Le champ « nom du produit » est obligatoire.']);
            return;
        }

        if (!is_numeric($data['product_priceUSD']) || $data['product_priceUSD'] <= 0) {
            echo json_encode(['message' => 'Le prix doit être un nombre positif.']);
            return;
        }

        if (!isset($data['product_stock_units']) || !is_numeric($data['product_stock_units']) || $data['product_stock_units'] < 0) {
            echo json_encode(['message' => 'Le stock doit être un entier non négatif.']);
            return;
        }

        // Mise à jour
        $query = $this->db->prepare("UPDATE wp_k_products SET product_maker = :maker, product_brand = :brand, product_tags_fr = :tags, product_line_fr = :line, product_category_fr = :category, product_subcategory_fr = :subCategory, product_name_fr = :name, product_image = :image, product_stock_units = :stockUnit, product_stock_status = :stockStatus, product_priceUSD = :priceUSD, product_priceEUR = :priceEUR, product_description_fr = :description WHERE product_aid = :aid");

        $query->bindValue(':maker', $data['product_maker']);
        $query->bindValue(':brand', $data['product_brand']);
        $query->bindValue(':tags', $data['product_tags_fr']);
        $query->bindValue(':line', $data['product_line_fr']);
        $query->bindValue(':category', $data['product_category_fr']);
        $query->bindValue(':subCategory', $data['product_subcategory_fr']);
        $query->bindValue(':name', $data['product_name_fr']);
        $query->bindValue(':image', $data['product_image']);
        $query->bindValue(':stockUnit', $data['product_stock_units'], PDO::PARAM_INT);
        $query->bindValue(':stockStatus', $data['product_stock_status']);
        $query->bindValue(':priceUSD', $data['product_priceUSD']);
        $query->bindValue(':priceEUR', $data['product_priceEUR']);
        $query->bindValue(':description', $data['product_description_fr']);
        $query->bindValue(':aid', $id, PDO::PARAM_INT);

        echo json_encode($query->execute() ? ['message' => 'Produit mis à jour avec succès'] : ['message' => 'Erreur lors de la mise à jour du produit']);
    }

    public function deleteProduct($id)
    {
        $query = $this->db->prepare("DELETE FROM wp_k_products WHERE product_aid = :aid");
        $query->bindValue(":aid", $id, PDO::PARAM_INT);
        echo json_encode($query->execute() ? ['message' => 'Produit supprimé avec succès'] : ['message' => 'Erreur lors de la suppression du produit']);
    }
}

// Récupération de l'URL demandée
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Crée une instance de ProductController
$productController = new ProductController();

// Router les requêtes en fonction de l'URL et de la méthode HTTP
if ($requestMethod === 'GET' && preg_match('/\/api\/produits$/', $requestUri)) {
    $productController->getAllProducts();
} elseif ($requestMethod === 'GET' && preg_match('/\/api\/produits\/(\d+)$/', $requestUri, $matches)) {
    $productController->getProductById($matches[1]);
} elseif ($requestMethod === 'POST' && preg_match('/\/api\/produits$/', $requestUri)) {
    $productController->createProduct();
} elseif ($requestMethod === 'PUT' && preg_match('/\/api\/produits\/(\d+)$/', $requestUri, $matches)) {
    $productController->updateProduct($matches[1]);
} elseif ($requestMethod === 'DELETE' && preg_match('/\/api\/produits\/(\d+)$/', $requestUri, $matches)) {
    $productController->deleteProduct($matches[1]);
} else {
    echo json_encode(['message' => 'Endpoint non trouvé']);
}
?>
