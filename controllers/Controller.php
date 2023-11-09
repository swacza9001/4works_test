<?php

abstract class Controller {
    /**
     * 
     * @var array předává data do pohledů
     */
    protected array $data = array();
    
    /**
     * 
     * @var string pohled, který se má otevřít
     */
    protected string $view = "";
    
    /**
     * 
     * @var array predává data hlavičce stránky
     */
    protected array $header = array("title" => "", "keywords" => "", "description" => "");
    
    /**
     * abstraktní funkce určená ke zpracování požadavků
     */
    abstract function process (array $parameters) : void;
    
    /**
     * otevření pohledů
     * @return void
     */
    public function renderView() : void {
        if ($this->view) {
          // extract($this->safeEntities($this->data));
           extract($this->data);//, EXTR_PREFIX_ALL, "");
            require("views/" . $this->view . ".phtml");
        }
    }
    
    /**
     * přesměrování stránky
     * @param string $url
     * @return never
     */
    public function reroute(string $url): never {
        header("Location: /$url");
        header("Connection: close");
        exit;
    }
    
    /**
     * ošetření proměnných proti XSS
     * @param mixed $x 
     * @return mixed
     */
    private function safeEntities(mixed $x = null): mixed {
        if(!isset($x))
            return null;
        elseif(is_string($x))
            return htmlspecialchars($x, ENT_QUOTES);
        elseif(is_array($x)) {
            foreach($x as $k => $v) {
                $x[$k] = $this->safeEntities($v);
            }
            return $x;
        } else 
            return $x;
    }
    
    /**
     * ověření uživatele a jeho práv
     * @param bool $admin 
     * @return void
     */
    public function verifyUser(bool $admin = false): void {
        $userManager = new UserManager();
        $user = $userManager->getUser();
        if(!$user || ($admin && !$user['admin'])) {
            $this->addMessage('danger', 'Nemáte dostatečná oprávnění. Přihlašte se nebo se registrujte.');
            $this->reroute('registration');
        }
    }
    
    /**
     * přidávání zpráv
     * @param string $style způsob ostylování zprávy
     * @param string $message zpráva k zobrazení
     * @return void
     */
    public function addMessage(string $style, string $message): void {
        if (isset($_SESSION['messages'])){
            $_SESSION['messages'][] = array('style' => $style, 'message' => $message);
        }else{
            $_SESSION['messages'] = array(array('style' => $style, 'message' => $message));
            
        }
    }
    
    /**
     * předávání zpráv ze SESSION
     * @return array
     */
    public function getMessages(): array
    {
        if (isset($_SESSION['messages'])) {
            $messages = $_SESSION['messages'];
            unset($_SESSION['messages']);
            return $messages;
        } else 
            return array();
    }
}