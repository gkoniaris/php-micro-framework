<?php
namespace App\Middlewares;
use App\Singletons\Request;
class GetMethodMiddleware extends BaseMiddleware{

    protected $request;
    
    public function __construct(Request $request){
        $this->request = $request;
        parent::__construct();
    }

    protected function handle(){
        try{
            if ($this->request->getMethod() !== 'GET') {
                throw new \Exception('You can only use get method for this request');
            }
        }
        catch(\Exception $e) {
            $this->request->terminateRequestWithException($e);
        }
    }
}