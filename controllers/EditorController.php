<?php

class EditorController extends Controller {

    public function process(array $parameters): void {
        $productManager = new ProductManager();

        $userManager = new UserManager();
        $user = $userManager->getUser();
        $this->data['admin'] = $user && $user['admin'];

        $this->header = [
            "title" => "Vkládání produktů",
            "keywords" => "vložení, produkty, nové",
            "description" => "Vkládání produktů do systému",
        ];

        $productInfo = [
            "product_id" => "",
            "name" => "",
            "description" => "",
            "price" => "",
        ];

        if ($_POST) {
            $keys = ['name', 'description', 'price', 'image_path'];
            $productInfo = array_intersect_key($_POST, array_flip($keys));

            if (isset($_FILES['image'])) {
                $imagePath = $this->imagePath($_FILES['image']);
                if ($imagePath) {
                    $productInfo['image_path'] = $imagePath;
                }
            }

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
        } else if (!empty($parameters[0])) {
            $productToUpdate = $productManager->getProduct($parameters[0]);
            if ($productToUpdate) {
                $productInfo = $productToUpdate;
            } else {
                $this->reroute('error');
            }
        }

        $this->data['productInfo'] = $productInfo;
        $this->view = 'editor';
    }

    public function imagePath(array $image) {
        $validExtensions = ['png', 'jpeg', 'jpg'];
        $file = [];
        $file['name'] = $image['name'];

        // Define the target directory for storing the images.
        $targetDirectory = 'images/';

        // Construct the full path to the target file.
        $targetFile = $targetDirectory . $file['name'];
        $file['extension'] = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file extension is valid.
        if (in_array($file['extension'], $validExtensions)) {
            // Check for errors during file upload.
            if ($image['error'] === UPLOAD_ERR_OK) {
                // Attempt to move the uploaded file to the target directory.
                if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                    return $targetFile;
                }
            }
        } else if ((!in_array($file['extension'], $validExtensions)) && ($file['name'] !== "")) {
            $this->addMessage('danger', 'Špatný formát obrázku. Povolené: jpg, jpeg, png.');
        }

        return null;
    }

}
