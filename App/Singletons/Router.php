<?php
namespace App\Singletons;

use App\Patterns\Singleton;
class Router extends Singleton{

    protected static $instance;
    protected $routes;
    protected $request;

    /**
     * Router constructor.
     */
    protected function __construct()
    {
        $this->routes = [];
    }

    public function post($controllerText, $uri, $middlewares = [])
    {
        $this->initializeRouterItem($controllerText, $uri, $middlewares, 'POST');
    }

    public function get($controllerText, $uri, $middlewares = [])
    {
        $this->initializeRouterItem($controllerText, $uri, $middlewares, 'GET');
    }

    public function findRouteInstance($uri)
    {
        foreach($this->routes as $route){
            if($route['uri']==$uri) return $route;
        }
        return false;
    }

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function initializeRouterItem($controllerText, $uri, &$middlewares = [], $requestMethod)
    {
        $controller = $this->getControllerName($controllerText);
        $method = $this->getControllerMethod($controllerText);
        $this->injectMiddlewares($middlewares, $requestMethod);
        $this->routes[] = ['controller'=>$controller, 'method'=>$method, 'uri'=>$uri, 'middlewares'=>$middlewares];
    }

    private function getControllerName($controllerText)
    {
        $controllerParts = explode("@", $controllerText);
        return $controllerParts[0];
    }

    private function getControllerMethod($controllerText)
    {
        $controllerParts = explode("@", $controllerText);
        return $controllerParts[1];
    }

    private function injectMiddlewares(&$middlewares, $method)
    {
        switch ($method){
            case 'GET':
                array_unshift($middlewares, 'App\Middlewares\GetMethodMiddleware');
                break;
            case 'POST':
                array_unshift($middlewares, 'App\Middlewares\PostMethodMiddleware');
                break;
        }
        try{
            foreach($middlewares as $middleware){
                $class = class_exists($middleware);
                if(!$class) throw new \Exception('Invalid middleware class');
                $middlewareClass = is_subclass_of($middleware, 'App\Middlewares\BaseMiddleware');
                if(!$middlewareClass) throw new \Exception('Middlewares must extend BaseMiddleware class');
            }
        }
        catch(\Exception $e){
            Request::getInstance()->terminateRequestWithException($e);
        }
    }
}