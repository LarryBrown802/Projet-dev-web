<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function connect(): PDO
    {
        if (self::$instance === null) {
            // Configuration pour ton MySQL local sur Ubuntu
            $host = 'localhost';
            $db   = 'lems';
            $user = 'Nasus';
            $pass = '1SbireCanon@'; // Le mot de passe choisi à l'étape 2
            $port = '3306';

            try {
                self::$instance = new PDO(
                    "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données locale : " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}