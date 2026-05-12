<?php

namespace App\Models;

use PDO;

class PromotionModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ✅ NOUVEAU : Ajout de la pagination avec des valeurs par défaut
    public function getAll(int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->db->prepare('SELECT * FROM Promotion ORDER BY name ASC LIMIT :limit OFFSET :offset');
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ NOUVEAU : Le compteur total pour aider tes Contrôleurs à calculer les pages
    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(ID_promotion) FROM Promotion');
        return (int) $stmt->fetchColumn();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM Promotion WHERE ID_promotion = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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