<?php
session_start();
mb_internal_encoding("utf-8");

function autoloadClass (string $class) : void {
    if (preg_match('/Controller$/', $class))
            require("controllers/$class.php");
    else 
        require("models/$class.php");
}

spl_autoload_register("autoloadClass");

Db::connect("127.0.0.1", "root", "", "product_manager");


$router = new RouterController();
$router->process(array($_SERVER["REQUEST_URI"]));
$router->renderView();
