<?php

class UserManager {
    /**
     * hashování hesla
     * @param string $password
     * @return string
     */
    public function getHash(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    /**
     * registrace uživatele
     * @param string $name uživatelské jméno
     * @param string $password heslo
     * @param string $year aktuální rok
     * @return void
     * @throws UserException
     */
    public function signIn(string $name, string $password, string $year): void {
        if ($year != date('Y'))
            throw new UserException('Špatně jsi vyplnil antispam.');
        $user = array(
            'username' => $name,
            'password' => $this->getHash($password),
        );
        try {
            Db::insert('users', $user);
        } catch (PDOException $ex) {
            throw new UserException('Uživatel s tímto jménem je již zaregistrovaný.');
        }
    }
    /**
     * přihlášení
     * @param string $name
     * @param string $password
     * @return void
     * @throws UserException
     */
    public function logIn(string $name, string $password): void {
        $user = Db::queryOne('SELECT user_id, username, admin, password '
                . 'FROM users '
                . 'WHERE username = ?', 
                array($name));
        if(!$user || !password_verify($password, $user['password']))
                throw new UserException('Neplatné přihlašovací údaje.');
        $_SESSION['user'] = $user;
    }
    /**
     * odhlášení
     * @return void
     */
    public function logOut(): void {
        unset($_SESSION['user']);
    }
    /**
     * ověření přihlášení uživatele
     * @return array|null
     */
    public function getUser(): ?array {
        if(isset($_SESSION['user']))
            return $_SESSION['user'];
        return null;
    }
}