<?php

namespace App\Models;

use PDO;
use Exception;

// ❌ Suppression de "extends PaginationController"
class CompanyManagementModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ✅ Ajout de la pagination (LIMIT et OFFSET)
    public function getAll(?string $search = null, int $limit = 50, int $offset = 0): array
    {
        $sql = '
            SELECT c.ID, c.name, c.email, c.number, c.description, c.average_mark,
                   COUNT(DISTINCT a.ID_profile) AS stagiaires
            FROM Company c
            LEFT JOIN Offer o ON o.ID_company = c.ID
            LEFT JOIN Apply a ON a.ID_offer = o.ID_offer
            WHERE 1=1
        ';
        $params = [];

        if (!empty($search)) {
            $sql .= ' AND c.name LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= ' GROUP BY c.ID ORDER BY c.name ASC';
        
        // Application de la limite
        $sql .= ' LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ NOUVEAU : Le compteur pour la pagination
    public function countAll(?string $search = null): int
    {
        $sql = 'SELECT COUNT(ID) FROM Company WHERE 1=1';
        $params = [];

        if (!empty($search)) {
            $sql .= ' AND name LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function create(string $name, string $email, string $number, string $description): bool
    {
        $stmt = $this->db->prepare('
            INSERT INTO Company (name, email, number, description)
            VALUES (:name, :email, :number, :description)
        ');
        return $stmt->execute([
            ':name'        => $name,
            ':email'       => $email,
            ':number'      => $number,
            ':description' => $description,
        ]);
    }

    public function update(int $id, string $name, string $email, string $number, string $description): bool
    {
        $stmt = $this->db->prepare('
            UPDATE Company SET name = :name, email = :email, number = :number, description = :description
            WHERE ID = :id
        ');
        return $stmt->execute([
            ':name'        => $name,
            ':email'       => $email,
            ':number'      => $number,
            ':description' => $description,
            ':id'          => $id,
        ]);
    }

    public function updateMark(int $id, float $mark): bool
    {
        $stmt = $this->db->prepare('UPDATE Company SET average_mark = :mark WHERE ID = :id');
        return $stmt->execute([':mark' => $mark, ':id' => $id]);
    }

    // ✅ CORRECTION : Utilisation de la suppression en cascade sécurisée
    public function delete(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            $this->db->prepare('
                DELETE FROM Apply WHERE ID_offer IN (
                    SELECT ID_offer FROM Offer WHERE ID_company = :id
                )
            ')->execute([':id' => $id]);

            $this->db->prepare('
                DELETE FROM Save_wishlist WHERE ID_offer IN (
                    SELECT ID_offer FROM Offer WHERE ID_company = :id
                )
            ')->execute([':id' => $id]);

            $this->db->prepare('
                DELETE FROM Offer WHERE ID_company = :id
            ')->execute([':id' => $id]);

            $this->db->prepare('
                DELETE FROM Note WHERE ID_company = :id
            ')->execute([':id' => $id]);

            $this->db->prepare('
                DELETE FROM Company WHERE ID = :id
            ')->execute([':id' => $id]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}