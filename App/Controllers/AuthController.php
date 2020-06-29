<?php

namespace App\Controllers;

use App\Classes\User;
use App\Singletons\Request;
use App\Singletons\Response;

class AuthController extends BaseController
{
    protected $user;

    public function __construct()
    {
        $this->user = new User;
    }

    public function login()
    {
        $data = Request::data();
        
        $logged = $this->user->login($data);

        if($logged) {
            $responseData = ['status' => 'success', 'message' => 'You have been successfully logged in'];
            
            return Response::json($responseData);
        }

        $responseData = ['status' => 'failure', 'message' => 'Please enter valid credentials'];

        return Response::json($responseData);
    }
}