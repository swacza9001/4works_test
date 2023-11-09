<?php


class EditorController extends Controller {

    /**
     * Editor pro přídavání a úpravu produktů
     * @param array $parameters
     * @return void
     */
    public function process(array $parameters): void {
        //inicializace modelů-manažerů
        $productManager = new ProductManager();

        $userManager = new UserManager();
        $user = $userManager->getUser();
        $this->data['admin'] = $user && $user['admin'];
        
        //nastavení hlavičky stránky
        $this->header = [
            "title" => "Vkládání produktů",
            "keywords" => "vložení, produkty, nové",
            "description" => "Vkládání produktů do systému",
        ];
        
        //inicializace pole pro předání informací o produktech
        $productInfo = [
            "product_id" => "",
            "name" => "",
            "description" => "",
            "price" => "",
        ];
        
        //při odeslání formuláře 
        if ($_POST) {
            //inicializace pole klíčů a určení shody s daty z odeslaného formuláře
            $keys = ['name', 'description', 'price', 'image_path'];
            $productInfo = array_intersect_key($_POST, array_flip($keys));
            
            //uložení obrázku pokud je uploadován
            if (isset($_FILES['image'])) {
                $imagePath = $this->imagePath($_FILES['image']);
                if ($imagePath) {
                    $productInfo['image_path'] = $imagePath;
                }
            }
            
            //pokud již produkt existuje, je updatován, pokud ne, je vytvořen, zobrazena informace uživateli o provedené akci a přesměrování na stránku produktu
            if ($_POST['product_id']) {
                print_r($productInfo);
                $productManager->updateProduct($_POST['product_id'], $productInfo);
                $this->addMessage('success', 'Produkt byl upraven');
                $this->reroute('products/' . $_POST['product_id']);
            } else {
                $productManager->insertProduct($productInfo);
                $this->addMessage('success', 'Produkt byl přidán');
                $lastId = Db::lastId();
                $this->reroute('products/' . $lastId);
            }
        //předvyplnění polí, když ještě není odeslán formulář
        } else if (!empty($parameters[0])) {
            $productToUpdate = $productManager->getProduct($parameters[0]);
            if ($productToUpdate) {
                $productInfo = $productToUpdate;
            } else {
                $this->reroute('error');
            }
        }
        //předání dat do pohledu
        $this->data['productInfo'] = $productInfo;
        //vyvolání pohledu
        $this->view = 'editor';
    }
    
    /**
     * Uložení obrázku a získání cesty k uložení do DB
     * @param array $image superglobální pole $_FILES
     * @return string návratová hodnota odpovídající relativní cestě k obrázku pro uložení do DB
     */
    public function imagePath(array $image) {
        //akceptované formáty obrázků
        $validExtensions = ['png', 'jpeg', 'jpg'];
        $file = [];

        // vytvoření kompletní cesty k uložení obrázků
        $targetFile = 'images/' . $image['name'];
        $file['extension'] = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // ověření zda je formát obrázku přijatelný
        if (in_array($file['extension'], $validExtensions)) {
            // kontrola chyb při uploadu
            if ($image['error'] === UPLOAD_ERR_OK) {
                // přesunutí do cílové složky
                if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                    return $targetFile;
                }
            }
        // přidání zprávy, pokud je obrázek uploadován a je ve špatném formátu
        } else if ((!in_array($file['extension'], $validExtensions)) && ($file['name'] !== "")) {
            $this->addMessage('danger', 'Špatný formát obrázku. Povolené: jpg, jpeg, png.');
        }

        return null;
    }

}
