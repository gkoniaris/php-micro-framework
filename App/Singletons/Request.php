<?php
namespace App\Singletons;
use App\Patterns\Singleton;
class Request extends Singleton
{
    private $data;
    protected $method;
    protected $router;
    protected static $instance;
    /**
     * Request constructor.
     */
    protected function __construct(){
        $this->data = [];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->router = Router::getInstance();
        $this->fillData();
    }

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Getter method
     */
    public static function method(){
        return static::getInstance()->method;
    }

    /**
     * Getter method
     */
    public static function data(){
        return static::getInstance()->data;
    }

    /**
     * Terminates a request by returning a human readable message to the user
     * @param Exception [$e] An exception object that is passed to the function so we can get it's message
     */
    public function terminateRequestWithException($e){
        $response = ['status'=>'failure', 'message'=>$e->getMessage()];
        echo json_encode($response);
        die();
    }

    public function serve(){
        try{
            $currentUri = $this->getCurrentUri();
            $route = $this->router->findRouteInstance($currentUri);
            if(!$route){
                throw new \Exception('Route not found');
            }
            $controller = new $route['controller']($this->data);
            foreach($route['middlewares'] as $middleware){
                new $middleware(Request::getInstance());
            }
            $controller->{$route['method']}();
        }catch(\Exception $e){
            $this->terminateRequestWithException($e);
        }
    }

    private function getCurrentUri()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');
        return $uri;
    }

    /**
     * Fills the request singleton with the data of the http request based on the method that was used
     */
    private function fillData(){
        try{
            switch($this->method){
                case 'POST':
                    $data = file_get_contents('php://input');
                    $decodedData = json_decode($data);
                    if(!$decodedData){
                        throw new \Exception('invalid json provided');
                    }
                    $this->data = $decodedData;
                    break;
            }

        }
        catch(\Exception $e) {
            $this->terminateRequestWithException($e);
        }
    }


}