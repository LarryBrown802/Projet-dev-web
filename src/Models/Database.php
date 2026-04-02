<?php

namespace App\Models;

class Database
{
    public static function connect(): \PDO
    {
        try {
            $dsn = 'mysql:host=' . $_ENV['DB_HOST']
                 . ';dbname=' . $_ENV['DB_NAME']
                 . ';charset=' . $_ENV['DB_CHARSET'];

            $db = new \PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}