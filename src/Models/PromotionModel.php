<?php

namespace App\Models;

class PromotionModel
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM Promotion ORDER BY name ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM Promotion WHERE ID_promotion = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(string $name): int|false
    {
        $stmt = $this->db->prepare('INSERT INTO Promotion (name) VALUES (:name)');
        $result = $stmt->execute([':name' => $name]);
        return $result ? (int) $this->db->lastInsertId() : false;
    }

    public function update(int $id, string $name): bool
    {
        $stmt = $this->db->prepare('UPDATE Promotion SET name = :name WHERE ID_promotion = :id');
        return $stmt->execute([':name' => $name, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Promotion WHERE ID_promotion = :id');
        return $stmt->execute([':id' => $id]);
    }
}