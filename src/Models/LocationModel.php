<?php

namespace App\Models;

class LocationModel
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM Location ORDER BY city ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByCity(string $city): array|false
    {
        $stmt = $this->db->prepare('
            SELECT * FROM Location WHERE city = :city LIMIT 1
        ');
        $stmt->execute([':city' => $city]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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