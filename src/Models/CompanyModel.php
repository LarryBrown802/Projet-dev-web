<?php

namespace App\Models;
use App\Utils\PaginationController;

class CompanyModel extends PaginationController
{
    private \PDO $db;
    protected int $parPage = 6;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(?string $search = null): array
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

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare('
            SELECT c.*, COUNT(DISTINCT o.ID_offer) AS offres_count
            FROM Company c
            LEFT JOIN Offer o ON o.ID_company = c.ID
            WHERE c.ID = :id
            GROUP BY c.ID
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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
            UPDATE Company SET name = :name, email = :email, 
                               number = :number, description = :description
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

    public function addNote(int $id_user, int $id_company, int $value): bool
    {
        // Insère ou met à jour la note
        $stmt = $this->db->prepare('
            INSERT INTO Note (ID_user, ID_company, value)
            VALUES (:id_user, :id_company, :value)
            ON DUPLICATE KEY UPDATE value = :value2
        ');
        $result = $stmt->execute([
            ':id_user'    => $id_user,
            ':id_company' => $id_company,
            ':value'      => $value,
            ':value2'     => $value,
        ]);

        // Recalcule la moyenne
        if ($result) {
            $avg = $this->db->prepare('
                SELECT AVG(value) FROM Note WHERE ID_company = :id
            ');
            $avg->execute([':id' => $id_company]);
            $moyenne = round((float) $avg->fetchColumn(), 1);
            $this->updateMark($id_company, $moyenne);
        }

        return $result;
    }

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

        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}