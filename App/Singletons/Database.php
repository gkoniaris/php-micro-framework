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
            
            $connection = new \PDO($dsn, $settings['DB_USERNAME'], $settings['DB_PASSWORD'], [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::MYSQL_ATTR_DIRECT_QUERY => false,
                \PDO::ATTR_ERRMODE=> \PDO::ERRMODE_EXCEPTION
            ]);

            return $connection;
        }
        catch(\Exception $e){
            die($e->getMessage());
        }
    }

    public static function select($query, $arguments = [])
    {
        $initialStmt = static::getInstance()::$connection->prepare($query);
        $initialStmt->execute($arguments);

        return $initialStmt->fetch();
    }

    public static function selectAll($query, $arguments = [])
    {
        $initialStmt = static::getInstance()::$connection->prepare($query);
        $initialStmt->execute($arguments);

        return $initialStmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function insert($query, $arguments = [])
    {
        $initialStmt = static::getInstance()::$connection->prepare($query);
        return $initialStmt->execute($arguments);
    }

    public static function create($query)
    {
        return static::getInstance()::$connection->exec($query);
    }

    public static function execute($query) 
    {
        return static::getInstance()::$connection->exec($query);
    } 
}