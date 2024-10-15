<?php

class Product {
    private int $product_aid;
    private string $product_maker;
    private string $product_brand;
    private string $product_tags_fr;
    private string $product_line_fr;
    private string $product_category_fr;
    private string $product_subcategory_fr;
    private string $product_name_fr;
    private string $product_image;
    private int $product_stock_units;
    private string $product_stock_status;
    private float $product_priceUSD;
    private float $product_priceEUR;
    private string $product_create_at; // Changé en string pour stocker une date au format string
    private string $product_description_fr;
    private PDO $db;

    public function __construct(PDO $db, int $aid) {
        $this->db = $db;

        // Préparation de la requête pour récupérer le produit par son `product_aid`
        $query = $db->prepare("SELECT * FROM wp_k_products WHERE product_aid = :aid");
        $query->bindValue(":aid", $aid, PDO::PARAM_INT);
        $query->execute();

        $product = $query->fetch(PDO::FETCH_OBJ);

        if ($product) {
            $this->product_aid = $product->product_aid;
            $this->product_maker = $product->product_maker ?? '';
            $this->product_brand = $product->product_brand ?? '';
            $this->product_tags_fr = $product->product_tags_fr ?? '';
            $this->product_line_fr = $product->product_line_fr ?? '';
            $this->product_category_fr = $product->product_category_fr ?? '';
            $this->product_subcategory_fr = $product->product_subcategory_fr ?? '';
            $this->product_name_fr = $product->product_name_fr ?? '';
            $this->product_image = $product->product_image ?? '';
            $this->product_stock_units = $product->product_stock_units ?? 0;
            $this->product_stock_status = $product->product_stock_status ?? '';
            $this->product_priceUSD = $product->product_priceUSD ?? 0.0; // Doit être un float
            $this->product_priceEUR = $product->product_priceEUR ?? 0.0; // Doit être un float
            $this->product_create_at = $product->product_create_at ?? date('Y-m-d H:i:s'); // Valeur par défaut
            $this->product_description_fr = $product->product_description_fr ?? '';
        } else {
            throw new Exception("Produit non trouvé avec l'ID : " . $aid);
        }
    }

    // Getters pour récupérer les valeurs des propriétés
    public function getProductAid(): int {
        return $this->product_aid;
    }

    public function getProductMaker(): string {
        return $this->product_maker;
    }

    // Ajoutez d'autres getters si nécessaire

    // Méthodes pour mettre à jour les valeurs si besoin
    public function setProductMaker(string $maker): void {
        $this->product_maker = $maker;
    }

    // Ajoutez d'autres setters si nécessaire
}
?>
