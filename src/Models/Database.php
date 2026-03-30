<?php
namespace App\Models;

class Database
{
    public static function connect(): \PDO
    {
        try {
            $db = new \PDO('mysql:host=localhost;dbname=Lems;charset=utf8mb4', 'root', 'Sv132006!');
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}