<?php

class ProductManager {
    
    public function getAllProducts(): array {
        return Db::queryAll('SELECT product_id, name, image_path '
                . 'FROM products '
                . 'ORDER BY name');
    }
    
    public function getProduct(int $id): array {
        return Db::queryOne('SELECT * '
                . 'FROM products '
                . 'WHERE product_id = ?', 
                array($id));
    }
    
    public function insertProduct(array $productInfo): bool {
        return Db::insert('products', $productInfo);
    }
    
    public function updateProduct(int $id, array $productInfo): bool {
        return Db::update('products', $productInfo, 'WHERE product_id = ?', array($id));
    }
    
    public function searchForProduct (array $whatFor) : array {
        return Db::queryAll("SELECT * "
                . "FROM products "
                . "WHERE MATCH(name, description) "
                . "AGAINST (?)", 
                array_values($whatFor));
    }
    
    public function getPaths(): array {
        return Db::queryAll('SELECT image_path '
                . 'FROM products');
    }
}