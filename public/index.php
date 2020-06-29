<?php

spl_autoload_register(function ($class) {
    require_once('../' . str_replace('\\', '/', $class) . '.php');
});

require_once('../App/config.php');
require_once('../App/middlewares.php');
require_once('../App/routes.php');

$request = App\Singletons\Request::getInstance();
$request->serve();