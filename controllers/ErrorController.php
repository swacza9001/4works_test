<?php

class ErrorController extends Controller {
    /**
     * kontroler chyby
     * @param array $parameters
     * @return void
     */
    public function process(array $parameters): void {
        header("HTTP/1.0 404 Not Found");
        $this->header['title'] = 'Chyba 404';
        $this->view = 'error';
    }
}