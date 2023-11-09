<?php

class ProductManager {
    
    /**
     * získání všech produktů z DB
     * @return array
     */
    public function getAllProducts(): array {
        return Db::queryAll('SELECT product_id, name, image_path '
                        . 'FROM products '
                        . 'ORDER BY name');
    }

    /**
     * Získání jednoho produktu z DB dle jeho id
     * @param int $id
     * @return array|bool
     */
    public function getProduct(int $id): array|bool {
        return Db::queryOne('SELECT * '
                        . 'FROM products '
                        . 'WHERE product_id = ?',
                        array($id));
    }

    /**
     * Vkládání produktu
     * @param array $productInfo
     * @return bool
     */
    public function insertProduct(array $productInfo): bool {
        return Db::insert('products', $productInfo);
    }

    /**
     * Update produktu podle jeho ID
     * @param int $id
     * @param array $productInfo
     * @return bool
     */
    public function updateProduct(int $id, array $productInfo): bool {
        return Db::update('products', $productInfo, 'WHERE product_id = ?', array($id));
    }

    /**
     * Vyhledávání produktů
     * @param array $whatFor klíčové slovo pro hledání produktu
     * @return array
     */
    public function searchForProduct(string $searchTerm): array {
        return Db::queryAll("SELECT * "
                        . "FROM products "
                        . "WHERE name LIKE ? OR description LIKE ?",
                        array("%" . $searchTerm . "%", "%" . $searchTerm . "%"));
    }
    
    /**
     * Získání cest k obrázkům produktů
     * @return array
     */
    public function getPaths(): array {
        return Db::queryAll('SELECT image_path '
                        . 'FROM products');
    }
    
    /**
     * Mazání produktů podle id z DB a smazání obrázku z adresáře
     * @param int $id
     * @param string $filePath
     * @return bool
     */
    public function deleteProduct(int $id, string $filePath): bool {
        unlink($filePath);
        return Db::query('DELETE FROM products '
                        . 'WHERE product_id = ' . $id);
    }

}
