<?php
use App\Singletons\Request;

$generalMiddlewares = [
];

$request = Request::getInstance();

foreach($generalMiddlewares as $middleware){
    new $middleware($request);
}