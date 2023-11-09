<?php

class RouterController extends Controller {
    
    protected Controller $controller;
    /**
     * 
     * @param array $parameters parametry v URL
     * @return void
     */
    public function process(array $parameters): void {  
        //naparsování URL
        $parsedURL = $this->parseURL($parameters[0]);
        
        //směrování na úvodní stránku
        if (empty($parsedURL[0]))
            $this->reroute('products'); 
        //vyvolání potřebného kontroleru
        $controllerClass = $this->dashToCamel(array_shift($parsedURL)) . 'Controller';
        if (file_exists('controllers/' . $controllerClass . '.php'))
                $this->controller = new $controllerClass;
        else
            $this->reroute('error');
        
        $this->controller->process($parsedURL);
        
        if (isset($_POST['search'])) {
            $_SESSION['search'] = $_POST['search'];
        }
        
        //předání dat
        $this->data['title'] = $this->controller->header['title'];
        $this->data['description'] = $this->controller->header['description'];
        $this->data['keywords'] = $this->controller->header['keywords'];
        $this->data['messages'] = $this->getMessages();
        $this->view = 'template';
    }
    /**
     * parsování URL na vhodný formát
     * @param string $url URL stránky 
     * @return array
     */
    private function parseURL(string $url): array {
        $parsedURL = parse_url($url);
        $parsedURL["path"] = ltrim($parsedURL["path"], "/");
        $parsedURL["path"] = trim($parsedURL["path"]);
        $explodedPath = explode("/", $parsedURL["path"]);
        return $explodedPath;
    }
    /**
     * úprava textu na formát potřebný k vyvolání kontroleru
     * @param string $text
     * @return string
     */
    private function dashToCamel(string $text): string {
        $sentence = str_replace('-', ' ', $text);
        $sentence = ucwords($sentence);
        $sentence = str_replace(' ', '', $sentence);
        return $sentence;
    }
    
}