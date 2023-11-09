<?php

class LogInController extends Controller {
    /**
     * přihlášení
     * @param array $parameters 
     * @return void
     */
    public function process(array $parameters): void {
        $userManager = new UserManager();
        //pokud je uživatel přihlášený, přesměrování na administraci
        if($userManager->getUser())
            $this->reroute('administration');
        $this->header['title'] = 'Přihlášení';
        //přihlášení
        if($_POST) {
            try {
                $userManager->logIn($_POST['name'], $_POST['password']);
                $this->addMessage('success', 'Jste přihlášen.');
                $this->reroute('administration');
            } catch (UserException $ex) {
                $this->addMessage('danger', 'Neplatné přihlašovací údaje.');
            }
        }
        $this->view = 'logIn';
    }
}