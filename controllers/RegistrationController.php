<?php

class RegistrationController extends Controller {
    /**
     * registrace
     * @param array $parameters
     * @return void
     */
    public function process(array $parameters): void {
        $this->header['title'] = 'Registrace';
        
        //uložení nového uživatele do DB a jeho přihlášení
        if($_POST) {
            try {
                $userManager = new UserManager();
                $userManager->signIn($_POST['name'], $_POST['password'], $_POST['year']);
                $userManager->logIn($_POST['name'], $_POST['password']);
                $this->addMessage('success', 'Byl jste úspěšně zaregistrován.');
                $this->reroute('administration');
            } catch (UserException $ex) {
                $this->addMessage('danger', $ex->getMessage());
            }
        }
        
        //výběr pohledu
        $this->view = 'registration';
    }
    
}