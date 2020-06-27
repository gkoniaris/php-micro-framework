<?php

namespace App\Handlers;

class Database{
    public $connection;
    public function __construct()
    {
        $this->connection = $this->initializeConnection();
    }

    private function initializeConnection()
    {
        global $settings;
        try{
            $dsn = "mysql:host=" . $settings['DB_HOST'] . ";dbname=" . $settings['DB_NAME'];
            $connection = new \PDO($dsn, $settings['DB_USERNAME'], $settings['DB_PASSWORD'], null);
            return $connection;
        }
        catch(\Exception $e){
            die($e->getMessage());
        }
    }
}