<?php

class ProductsController extends Controller {

    public function process(array $parameters): void {
        $productManager = new ProductManager();

        $userManager = new UserManager();
        $user = $userManager->getUser();
        $this->data['admin'] = $user && $user['admin'];

        if (!empty($parameters[0])) {
            $product = $productManager->getProduct($parameters[0]);
            $this->data['product'] = $product;

            if (!$product)
                $this->reroute('error');

            $this->header = [
                "title" => $product['name'],
                "keywords" => "zboží, detail, " . $product['name'],
                "description" => $product['description'],
            ];

            $this->view = "product";
        } else {

            if (isset($_SESSION['search'])) {
                $searchQuery = [];
                $searchQuery[] = $_SESSION['search'];
                $this->data['products'] = $productManager->searchForProduct($searchQuery);
                $paths = $productManager->getPaths();
                $this->data['first_random_path'] = $this->randomValue($this->getPaths($paths));
                $this->data['second_random_path'] = $this->randomValue($this->getPaths($paths));
                $this->data['third_random_path'] = $this->randomValue($this->getPaths($paths));
                unset($_SESSION['search']);
            } else {
                $this->data['products'] = $productManager->getAllProducts();

                $this->data['first_random_path'] = $this->randomValue($this->getPaths($this->data['products']));
                $this->data['second_random_path'] = $this->randomValue($this->getPaths($this->data['products']));
                $this->data['third_random_path'] = $this->randomValue($this->getPaths($this->data['products']));

                $this->data['image_paths'] = $this->getPaths($this->data['products']);

                $this->header = [
                    "title" => "Všechny naše produkty",
                    "keywords" => "veškeré zboží",
                    "description" => "Všechno naše zboží",
                ];
            }

            $this->view = "products";
        }
    }

    private function getIds(array $productsArray): array {
        $productIds = [];
        foreach ($productsArray as $product) {
            if (isset($product['product_id'])) {
                $productIds[] = $product['product_id'];
            }
        }
        return $productIds;
    }

    private function getPaths(array $productsArray): array {
        $imagePaths = [];
        foreach ($productsArray as $product) {
            if (isset($product['image_path']) && $product['image_path'] !== "") {
                $imagePaths[] = $product['image_path'];
            }
        }
        return $imagePaths;
    }

    private function randomValue(array $array) {
        $a = array_rand($array);
        $b = $array[$a];
        return $b;
    }

}
