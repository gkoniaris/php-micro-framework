<?php

$classesArray = [
    '../app/Patterns/Singleton.php',
    '../app/Singletons/Request.php',
    '../app/Singletons/Response.php',
    '../app/Singletons/Router.php',
    '../app/Singletons/Database.php',
    '../app/Middlewares/BaseMiddleware.php',
    '../app/Middlewares/Authenticated.php',
    '../app/Middlewares/GetMethodMiddleware.php',
    '../app/Middlewares/PostMethodMiddleware.php',
    '../app/Classes/User.php',
    '../app/Controllers/BaseController.php',
    '../app/Controllers/AuthController.php',
    '../app/Controllers/LoginController.php'
];

foreach($classesArray as $item){
    require_once($item);
}

require_once('../app/config.php');
require_once('../app/middlewares.php');
require_once('../app/routes.php');
$request = App\Singletons\Request::getInstance();
$request->serve();