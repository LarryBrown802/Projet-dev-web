<?php

namespace App\Models;

use PDO;

class LocationModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ✅ NOUVEAU : Standardisation avec la pagination
    public function getAll(int $limit = 500, int $offset = 0): array
    {
        $stmt = $this->db->prepare('SELECT * FROM Location ORDER BY city ASC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ NOUVEAU : Compteur total
    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(ID_location) FROM Location');
        return (int) $stmt->fetchColumn();
    }

    public function findByCity(string $city): array|false
    {
        $stmt = $this->db->prepare('
            SELECT * FROM Location WHERE city = :city LIMIT 1
        ');
        $stmt->execute([':city' => $city]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findOrCreate(string $city): int
    {
        // Cherche si la ville existe déjà
        $existing = $this->findByCity($city);

        if ($existing) {
            return $existing['ID_location']; // ← retourne l'ID existant
        }

        // Sinon on la crée
        $stmt = $this->db->prepare('
            INSERT INTO Location (city) VALUES (:city)
        ');
        $stmt->execute([':city' => $city]);
        return (int) $this->db->lastInsertId();
    }
}