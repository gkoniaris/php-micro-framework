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

    private function retrieveUser($email, $password){
        $initialStmt = Database::getInstance()->query()->prepare('SELECT * FROM users WHERE email = ?');
        $initialStmt->execute([$email]);
        $initialUser = $initialStmt->fetch();

        if(!$initialUser){
            return false;
        }
        
        $passwordHashed = hash('sha256' , $initialUser['salt'] . '.' . $password);

        $stmt = Database::getInstance()->query()->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
        $stmt->execute([$email, $passwordHashed]);
        $user = $stmt->fetch();

        return $user;
    }

    private function saveSession($userId)
    {
        session_start();
        setcookie(session_name(),session_id(),time()+(60*60), '/');

        $uniqueId = uniqid();
        $_SESSION['unique_id'] = $uniqueId;
        
        $stmt = Database::getInstance()->query()->prepare("INSERT INTO sessions(user_id, session_id, expires_at) VALUES (?, ?, CURRENT_TIMESTAMP + INTERVAL 1 DAY)");
        $stmt->execute([$userId, $uniqueId]);
    }
}