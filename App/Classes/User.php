<?php
namespace App\Classes;

use App\Singletons\Database;

class User{

    public function __construct(){
    
    }    

    public function login($data)
    {
        $email = $data->email;
        $password = $data->password;

        $user = $this->retrieveUser($email, $password);
        
        if($user){
            $this->saveSession($user['id']);
            return true;
        }

        return false;
    }

    private function retrieveUser($email, $password)
    {
        $initialUser = Database::select('SELECT * FROM users WHERE email = ?', [$email]);

        if(!$initialUser){
            return false;
        }
        
        $passwordHashed = hash('sha256' , $initialUser['salt'] . '.' . $password);

        $user = Database::select('SELECT * FROM users WHERE email = ? AND password = ?', [$email, $passwordHashed]);

        return $user;
    }

    private function saveSession($userId)
    {
        session_start([
            'cookie_lifetime' => 86400,
        ]);
     
        $uniqueId = uniqid('', TRUE); // Add true for more entropy
        $_SESSION['unique_id'] = $uniqueId;
        
        $stmt = Database::insert("INSERT INTO sessions(user_id, session_id, expires_at) VALUES (?, ?, CURRENT_TIMESTAMP + INTERVAL 1 DAY)", [$userId, $uniqueId]);
    }
}