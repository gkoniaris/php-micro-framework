<?php
use App\Singletons\Router;

$router = Router::getInstance();
$router->get('App\Controllers\IndexController@index', '/');
$router->post('App\Controllers\AuthController@login', '/api/login');
// $router->post('App\Controllers\AuthController@register', '/api/register');
