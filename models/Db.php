<?php

class Db {

    private static PDO $connection;

    /**
     * nastavení PDO spojení
     * @var array
     */
    private static array $settings = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /**
     * připojení k databázi
     * @param string $host IP adresa serveru
     * @param string $user username uživatele databáze 
     * @param string $password heslo k databázi
     * @param string $dbName jméno databáze
     * @return void
     */
    public static function connect(string $host, string $user, string $password, string $dbName): void {
        if (!isset(self::$connection)) {
            self::$connection = new PDO("mysql:host=$host;dbname=$dbName", $user, $password, self::$settings);
        }
    }

    /**
     * volání databáze pro jeden výsledek
     * @param string $query string SQL dotazu
     * @param array $parameters parametry dotazu
     * @return array|bool
     */
    public static function queryOne(string $query, array $parameters = array()): array|bool {
        $return = self::$connection->prepare($query);
        $return->execute($parameters);
        return $return->fetch();
    }

    /**
     * volání databáze pro více výsledků
     * @param string $query string SQL dotazu
     * @param array $parameters parametry dotazu
     * @return array|bool
     */
    public static function queryAll(string $query, array $parameters = array()): array|bool {
        $return = self::$connection->prepare($query);
        $return->execute($parameters);
        return $return->fetchAll();
    }

    /**
     * volání databáze pro jeden sloupec
     * @param string $query string SQL dotazu
     * @param array $parameters parametry dotazu
     * @return array|bool
     */
    public static function fetchColumn(string $query, array $parameters = array()): array|bool {
        $return = self::$connection->prepare($query);
        $return->execute($parameters);
        return $return->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * volání databáze pro více výsledků, zjednodušené asociativní pole
     * @param string $query string SQL dotazu
     * @param array $parameters parametry dotazu
     * @return array|bool
     */
    public static function fetchAssociated(string $query, array $parameters = array()): array|bool {
        $return = self::$connection->prepare($query);
        $return->execute($parameters);
        return $return->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * volání databáze pro počet ovlivněných řádků
     * @param string $query string SQL dotazu
     * @param array $parameters parametry dotazu
     * @return array|bool
     */
    public static function query(string $query, array $parameters = array()): int {
        $return = self::$connection->prepare($query);
        $return->execute($parameters);
        return $return->rowCount();
    }

    /**
     * vrácení posledního vloženého ID
     * @return int
     */
    public static function lastId(): int {
        return self::$connection->lastInsertId();
    }

    /**
     * volání k vkládání dat do databáze
     * @param string $table jméno tabulky
     * @param array $parameters parametry dotazu
     * @return bool
     */
    public static function insert(string $table, array $parameters = array()): bool {
        return self::query("INSERT INTO $table (". 
                implode(', ', array_keys($parameters)).
                ") VALUES (". str_repeat('?, ', sizeof($parameters)-1)."?)",
                array_values($parameters));
    }

    /**
     * volání ke změně dat v databázi
     * @param string $table jméno tabulky
     * @param array $values sloupce ke změně
     * @param string $condition podmínka dotazu
     * @param array $parameters parametry dotazu
     * @return bool
     */
    public static function update(string $table, array $values, string $condition, array $parameters = array()): bool {
    return self::query("UPDATE $table SET " .
                    implode(' = ?, ', array_keys($values)) .
                    " = ? " . $condition,
                    array_merge(array_values($values), $parameters));
}

}
