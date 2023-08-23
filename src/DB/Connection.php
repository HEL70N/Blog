<?php

namespace Code\DB;

class Connection
{
    private static $instance = null;
    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new \PDO('mysql:host=127.0.0.1;dbname=my_expenses', 'root', '');
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$instance->exec('SET NAMES UTF8');
        }

        return self::$instance;
    }
}
