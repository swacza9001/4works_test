<?php

class ProductsController extends Controller {

    /**
     * Zobrazení produktů
     * @param array $parameters
     * @return void
     */
    public function process(array $parameters): void {
        //inicializace modelů-manažerů
        $productManager = new ProductManager();

        $userManager = new UserManager();
        $user = $userManager->getUser();
        $this->data['admin'] = $user && $user['admin'];

        //mazání produktů
        if(!empty($parameters[1]) && $parameters[1] == 'delete') {
            $this->verifyUser(true);
            
            //získaní informací o produktu kvůli smazání obrázku
            $product = $productManager->getProduct($parameters[0]);
            
            //samotné smazání z DB a obrázku ze složky
            $productManager->deleteProduct($parameters[0], $product['image_path']);
            
            //přidání zprávy o smazání a přesměrování
            $this->addMessage('warning', 'Zboží bylo smazáno');
            $this->reroute('products');
        //zobrazení jednotlivých produktů
        } else if (!empty($parameters[0])) {
            $product = $productManager->getProduct($parameters[0]);
            $this->data['product'] = $product;
            
            //přesměrování na chybovou stránku, při neexistujícím produktu
            if (!$product)
                $this->reroute('error');

            //vyplnění hlavičky stránky
            $this->header = [
                "title" => $product['name'],
                "keywords" => "zboží, detail, " . $product['name'],
                "description" => $product['description'],
            ];
            
            //výběr pohledu
            $this->view = "product";
        } else {
            //zobrazení stránky vyhledávaných produktů
            if (isset($_POST['search'])) {
                $this->data['products'] = $productManager->searchForProduct($_POST['search']);
                //data pro Bootstrapový carousel
                $paths = $productManager->getPaths();
                $this->data['first_random_path'] = $this->randomValue($this->getPaths($paths));
                $this->data['second_random_path'] = $this->randomValue($this->getPaths($paths));
                $this->data['third_random_path'] = $this->randomValue($this->getPaths($paths));
                
                //vyplnění hlavičky stránky
                $this->header = [
                    "title" => "Vyhledávané produkty: " . $_POST['search'],
                    "keywords" => "hledání, " . $_POST['search'],
                    "description" => "Produkty, které hledáte: " . $_POST['search'],
                ];
            //zobrazení všech produktů
            } else {
                $this->data['products'] = $productManager->getAllProducts();
                //data pro carousel
                $this->data['first_random_path'] = $this->randomValue($this->getPaths($this->data['products']));
                $this->data['second_random_path'] = $this->randomValue($this->getPaths($this->data['products']));
                $this->data['third_random_path'] = $this->randomValue($this->getPaths($this->data['products']));
                
                //vyplnění hlavičky
                $this->header = [
                    "title" => "Všechny naše produkty",
                    "keywords" => "veškeré zboží",
                    "description" => "Všechno naše zboží",
                ];
            }
            //výběr pohledu
            $this->view = "products";
        }
    }
    
    
    /**
     * Metoda pro získání cest k obrázkům využívané carouselem
     * @param array $productsArray
     * @return array
     */
    private function getPaths(array $productsArray): array {
        $imagePaths = [];
        foreach ($productsArray as $product) {
            if (isset($product['image_path']) && $product['image_path'] !== "") {
                $imagePaths[] = $product['image_path'];
            }
        }
        return $imagePaths;
    }

    /**
     * Vrací náhodnou hodnotu pole (pro obrázky pro carousel)
     * @param array $array
     * @return array
     */
    private function randomValue(array $array) {
        $a = array_rand($array);
        $b = $array[$a];
        return $b;
    }

}
