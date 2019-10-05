<?php

namespace core\db;

use core\Config;

class DB
{
    private static $instance = null;
    protected $connection;

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    private function __construct()
    {
        $dsn = Config::singleton()->get('database.driver')
            . ':host=' . Config::singleton()->get('database.host')
            . ':' . Config::singleton()->get('database.port')
            . ';dbname=' . Config::singleton()->get('database.name');

        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->connection = new \PDO($dsn, Config::singleton()->get('database.login'), Config::singleton()->get('database.password'), $options);
    }

    public static function getPDO() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance->connection;
    }
    
    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}