<?php
namespace App\Classes;

use \App\Handlers\Database;

class User{

    public function __construct(){
        $this->db = new Database();
    }

    public function login($data)
    {
        $username = $data->username;
        $password = $data->password;
        $user = $this->retrieveUser($username, $password);
        if($user){
            $this->saveSession($user['id']);
            return true;
        }
        return false;
    }

    private function retrieveUser($username, $password){
        $initialStmt = $this->db->connection->prepare('SELECT * FROM users WHERE username = ?');
        $initialStmt->execute([$username]);
        $initialUser = $initialStmt->fetch();

        if(!$initialUser){
            return false;
        }

        $passwordHashed = hash ('sha256' , $initialUser['salt'] . '.' . $password);

        $stmt = $this->db->connection->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        $stmt->execute([$username, $passwordHashed]);
        $user = $stmt->fetch();

        return $user;
    }

    private function saveSession($userId)
    {
        session_start();
        setcookie(session_name(),session_id(),time()+(60*60), '/');

        $uniqueId = uniqid();
        $_SESSION['unique_id'] = $uniqueId;
        
        $stmt = $this->db->connection->prepare("INSERT INTO sessions(user_id, session_id, expires_at) VALUES (?, ?, CURRENT_TIMESTAMP + INTERVAL 1 DAY)");
        $stmt->execute([$userId, $uniqueId]);
    }
}