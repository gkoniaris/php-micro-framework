<?php

namespace App\Controllers;
use App\Classes\User;
use App\Singletons\Request;

class LoginController extends BaseController
{
    protected $user;
    public function __construct(){
        $this->user = new User;
    }
    public function login()
    {
        $data = Request::getInstance()->getData();
        $logged = $this->user->login($data);

        if($logged){
            $responseData = ['status'=>'sucess', 'message'=>'You have been successfully logged in', 'redirectUrl' => '/dashboard'];
            echo json_encode($responseData);
            return;
        }

        $responseData = ['status'=>'failure', 'message'=>'Please enter valid credentials'];

        echo json_encode($responseData);
    }
}