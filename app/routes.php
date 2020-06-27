<?php
use App\Singletons\Router;

$router = Router::getInstance();
$router->get('App\Controllers\IndexController@index', '/');
$router->post('App\Controllers\LoginController@login', '/api/login');
