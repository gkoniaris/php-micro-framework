<?php
namespace App\Singletons;

use App\Patterns\Singleton;

class Response extends Singleton{

    protected static $instance;

    /**
     * Router constructor.
     */
    protected function __construct()
    {
    }

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function json($data) {
        header('Content-Type: application/json');

        echo json_encode($data);
    }
}