<?php

namespace App\Models;

use PDO;

class LocationModel
{
    /**
     * Récupère TOUTES les villes disponibles pour les formulaires.
     * C'est une bonne pratique professionnelle d'avoir tous les lieux pour pouvoir recruter partout.
     */
    public function getAllLocations(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query("SELECT ID_location, city FROM Location ORDER BY city")->fetchAll();
    }
}