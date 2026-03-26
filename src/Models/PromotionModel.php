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

    public function getByUser(int $id_user): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM Promotion WHERE ID_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(string $name, int $id_user): bool
    {
        $stmt = $this->db->prepare('
            INSERT INTO Promotion (name, ID_user) VALUES (:name, :id_user)
        ');
        return $stmt->execute([':name' => $name, ':id_user' => $id_user]);
    }

    public function update(int $id_promotion, string $name): bool
    {
        $stmt = $this->db->prepare('
            UPDATE Promotion SET name = :name WHERE ID_promotion = :id
        ');
        return $stmt->execute([':name' => $name, ':id' => $id_promotion]);
    }

    public function delete(int $id_promotion): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Promotion WHERE ID_promotion = :id');
        return $stmt->execute([':id' => $id_promotion]);
    }

    public function deleteByUser(int $id_user): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Promotion WHERE ID_user = :id_user');
        return $stmt->execute([':id_user' => $id_user]);
    }
}