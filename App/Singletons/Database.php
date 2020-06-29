<?php
namespace App\Singletons;

use App\Patterns\Singleton;

class Database extends Singleton {

    protected static $instance;
    protected static $connection;

    /**
     * Database constructor.
     */
    protected function __construct()
    {
        static::$connection = $this->initializeConnection();
    }

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
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


    public function query()
    {
        return static::getInstance()::$connection;
    }
}