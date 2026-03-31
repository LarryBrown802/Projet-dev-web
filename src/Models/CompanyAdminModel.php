<?php
/*
namespace App\Models;

class CompanyModel extends PaginationModel
{
    private \PDO $db;
    protected int $parPage = 5;

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

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Company WHERE ID = :id');
        return $stmt->execute([':id' => $id]);
    }
}
    */