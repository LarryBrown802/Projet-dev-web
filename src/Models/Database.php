<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    // C'est ici qu'on stocke l'unique connexion (le Singleton)
    private static ?PDO $instance = null;

    // Le constructeur est "privé" : impossible de faire un "new Database()" par erreur
    private function __construct() {}

    // La méthode magique que tout le monde va appeler
    public static function connect(): PDO
    {
        // Si la connexion n'existe pas encore, on la crée
        if (self::$instance === null) {
            try {
                // On récupère les identifiants cachés dans le fichier .env
                $host = $_ENV['DB_HOST'];
                $dbname = $_ENV['DB_NAME'];
                $user = $_ENV['DB_USER'];
                $password = $_ENV['DB_PASS'];
                $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

                // On prépare le chemin (DSN)
                $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

                // Les options de sécurité MAXIMALES
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Fait crasher PHP si SQL échoue (pour déboguer)
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Renvoie des tableaux propres (sans numéros)
                    PDO::ATTR_EMULATE_PREPARES => false, // Oblige MySQL à sécuriser les variables (Anti-Injection SQL)
                ];

                self::$instance = new PDO($dsn, $user, $password, $options);

            } catch (PDOException $e) {
                // En cas de problème (ex: mauvais mot de passe), on arrête tout proprement
                // En production, on cacherait le $e->getMessage() pour ne pas donner d'infos aux pirates
                die("Erreur critique de connexion à la base de données : " . $e->getMessage());
            }
        }

        // Si elle existait déjà, on la redonne directement sans rien recalculer !
        return self::$instance;
    }
}